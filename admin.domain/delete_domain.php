<?php
// dashboard_hapus_multi_domain_final_auto_unpark_manual_list.php
// Final drop-in: AUTO delete + aggressive unpark/wildcard attempts + UI manual-list.
// WARNING: run only in private/testing environment.

define('ENV_LOCAL', true);
if (ENV_LOCAL) {
    ini_set('display_errors','1'); ini_set('display_startup_errors','1'); error_reporting(E_ALL);
}
set_exception_handler(function($e){
    $msg = sprintf("Uncaught Exception: %s in %s on line %d\nStack: %s",
        $e->getMessage(), $e->getFile(), $e->getLine(), $e->getTraceAsString());
    if (defined('LOG_FILE') && LOG_FILE) @file_put_contents(LOG_FILE, "[".date('c')."] EXCEPTION: $msg\n", FILE_APPEND|LOCK_EX);
    if (ENV_LOCAL) echo "<pre>$msg</pre>"; else { http_response_code(500); echo "Internal Server Error"; }
    exit(1);
});

// ---------------- CONFIG ----------------
$dbHost = getenv('DB_HOST') ?: '127.0.0.1';
$dbUser = getenv('DB_USER') ?: 'gassstea_gasssnew';
$dbPass = getenv('DB_PASS') ?: 'gasssnewgasssnewgasssnew';
$dbName = getenv('DB_NAME') ?: 'gassstea_gasssnew';

// set table name used on your DB
$tableName = getenv('DOMAINS_TABLE') ?: 'addondomain';

// cPanel host & tokens — keep safe
$cpanelHost = getenv('CPANEL_HOST') ?: 'lax030.arandomserver.com';
// set to 'whm' if you supply root WHM token; otherwise leave empty for shared hosting
$cpanelApiType = getenv('CPANEL_API_TYPE') ?: '';
$cpanelUser = getenv('CPANEL_USER') ?: 'gassstea';
$cpanelToken = getenv('CPANEL_TOKEN') ?: 'ONUKIQL0U256I8VY4WKXUU9058B8NCPS';

$backupDir = __DIR__ . '/backups';
$logFile = __DIR__ . '/logs/delete_actions.log';
define('LOG_FILE', $logFile);

// Feature flags
$AUTO_CONFIRM = true;                // force auto delete (no manual "DELETE" input)
$USE_UAPI_FALLBACK = true;           // attempt uapi CLI (requires root or proper perms)

// Ensure dirs
foreach ([$backupDir, dirname($logFile)] as $d) {
    if (!is_dir($d) && !mkdir($d,0700,true) && !is_dir($d)) throw new RuntimeException("Cannot create dir $d");
    if (!is_writable($d)) throw new RuntimeException("Not writable: $d");
}

session_start();

// ---------------- HELPERS ----------------
function generate_csrf() {
    if (empty($_SESSION['csrf'])) {
        if (function_exists('random_bytes')) $_SESSION['csrf'] = bin2hex(random_bytes(16));
        elseif (function_exists('openssl_random_pseudo_bytes')) $_SESSION['csrf'] = bin2hex(openssl_random_pseudo_bytes(16));
        else $_SESSION['csrf'] = bin2hex(mt_rand() . microtime(true));
    }
    return $_SESSION['csrf'];
}
function verify_csrf($token) {
    return !empty($token) && !empty($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $token);
}
function log_action($msg) {
    global $logFile;
    $line = "[".date('Y-m-d H:i:s')."] $msg\n";
    @file_put_contents($logFile, $line, FILE_APPEND|LOCK_EX);
}
function sanitize_domains(array $lines): array {
    $out = [];
    foreach ($lines as $l) {
        $d = trim($l);
        if ($d === '') continue;
        if (preg_match('/^[a-z0-9-]+(\.[a-z0-9-]+)*\.[a-z]{2,}$/i', $d)) $out[] = strtolower($d);
    }
    return array_values(array_unique($out));
}
function mysqli_bind_params(mysqli_stmt $stmt, string $types, array $values): bool {
    $refs = []; $refs[] = &$types;
    foreach ($values as $i => $v) $refs[] = &$values[$i];
    return (bool) call_user_func_array([$stmt, 'bind_param'], $refs);
}
function safe_show_like(mysqli $mysqli, string $pattern) {
    $esc = $mysqli->real_escape_string($pattern);
    $sql = "SHOW TABLES LIKE '{$esc}'";
    $res = $mysqli->query($sql);
    if ($res === false) { log_action("safe_show_like failed: ".$mysqli->error." -- SQL: $sql"); return false; }
    $rows = []; while ($r = $res->fetch_row()) $rows[] = $r;
    $res->free(); return $rows;
}
function table_exists(mysqli $mysqli, string $tableName): bool {
    $rows = safe_show_like($mysqli, $tableName);
    return ($rows !== false && count($rows) > 0);
}
function backup_table_rows(mysqli $mysqli, string $tableName, array $domains): string {
    global $backupDir;
    $time = date('Ymd_His');
    $file = "$backupDir/{$tableName}_backup_{$time}.json";
    if (empty($domains)) return '';
    $placeholders = implode(',', array_fill(0, count($domains), '?'));
    $types = str_repeat('s', count($domains));
    $sql = "SELECT * FROM `".$mysqli->real_escape_string($tableName)."` WHERE domain IN ($placeholders)";
    $stmt = $mysqli->prepare($sql);
    if (!$stmt) { log_action("backup_table_rows prepare failed: ".$mysqli->error." -- SQL: $sql"); return ''; }
    if (!mysqli_bind_params($stmt, $types, $domains)) { $stmt->close(); log_action("backup bind failed"); return ''; }
    if (!$stmt->execute()) { log_action("backup execute failed: ".$stmt->error); $stmt->close(); return ''; }
    $rows = [];
    if (method_exists($stmt,'get_result')) { $res = $stmt->get_result(); if ($res) $rows = $res->fetch_all(MYSQLI_ASSOC); }
    else {
        $meta = $stmt->result_metadata();
        if ($meta) {
            $fields=[]; $row=[];
            while ($f = $meta->fetch_field()) { $fields[] = $f->name; $row[$f->name]=null; }
            $bindRefs=[]; foreach ($row as $k=>&$v) $bindRefs[]=&$row[$k];
            call_user_func_array([$stmt,'bind_result'],$bindRefs);
            while ($stmt->fetch()) { $r = []; foreach ($fields as $f) $r[$f]=$row[$f]; $rows[]=$r; }
            $meta->free();
        }
    }
    $w = @file_put_contents($file, json_encode(['meta'=>['created'=>date('c'),'count'=>count($rows),'table'=>$tableName],'rows'=>$rows], JSON_PRETTY_PRINT));
    if ($w===false) { log_action("backup write failed for $file"); $stmt->close(); return ''; }
    $stmt->close(); return $file;
}

// cPanel HTTP API
function cpanel_api_call(string $path, array $post = []) {
    global $cpanelHost, $cpanelApiType, $cpanelUser, $cpanelToken;
    if ($cpanelApiType === 'whm') { $port=2087; $authHeader="Authorization: WHM root:$cpanelToken"; $url="https://$cpanelHost:$port$path"; }
    else { $port=2083; $authHeader="Authorization: cpanel $cpanelUser:$cpanelToken"; $url="https://$cpanelHost:$port$path"; }
    if (!function_exists('curl_init')) return ['status'=>'error','msg'=>'cURL not available','code'=>0];
    $ch = curl_init(); curl_setopt($ch, CURLOPT_URL, $url);
    if (!empty($post)) { curl_setopt($ch, CURLOPT_POST, true); curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post)); }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [$authHeader, 'Expect:']);
    $out = curl_exec($ch); $err = curl_error($ch); $code = curl_getinfo($ch, CURLINFO_HTTP_CODE); curl_close($ch);
    if ($out === false) return ['status'=>'error','msg'=>"cURL error: $err",'code'=>$code];
    $decoded = json_decode($out, true);
    if ($decoded === null) return ['status'=>'raw','body'=>$out,'code'=>$code];
    return ['status'=>'ok','data'=>$decoded,'code'=>$code];
}

// uapi CLI helpers
function uapi_exec(array $parts, string $user = null) {
    $bin = '/usr/local/cpanel/bin/uapi';
    if (!file_exists($bin) || !is_executable($bin)) return ['status'=>'error','msg'=>"$bin not available"];
    $cmd = escapeshellcmd($bin);
    if (!empty($user)) $cmd .= ' --user=' . escapeshellarg($user);
    foreach ($parts as $p) $cmd .= ' ' . escapeshellarg($p);
    $out = []; $code = 0; exec($cmd . ' 2>&1', $out, $code);
    $outstr = implode("\n", $out);
    if ($code === 0) return ['status'=>'ok','out'=>$outstr];
    return ['status'=>'error','msg'=>"uapi exit $code: $outstr"];
}
function uapi_fetch_zone_records(string $domain, string $user = null) {
    $u = uapi_exec(['ZoneEdit','fetch_zone_records','domain='.$domain], $user);
    if ($u['status'] !== 'ok') return ['status'=>'error','msg'=>$u['msg'] ?? 'uapi fail'];
    $decoded = json_decode($u['out'], true);
    if ($decoded === null) return ['status'=>'raw','body'=>$u['out']];
    $data = $decoded['data'] ?? ($decoded['result']['data'] ?? ($decoded['cpanelresult']['data'] ?? []));
    return ['status'=>'ok','data'=>$data];
}
function uapi_remove_zone_record(string $domain, $line, string $user = null) {
    $u = uapi_exec(['ZoneEdit','remove_zone_record','domain='.$domain,'line='.$line], $user);
    if ($u['status'] !== 'ok') return ['status'=>'error','msg'=>$u['msg'] ?? 'uapi fail'];
    $decoded = json_decode($u['out'], true);
    if ($decoded === null) return ['status'=>'raw','body'=>$u['out']];
    return ['status'=>'ok','data'=>$decoded];
}

// aggressive unpark + wildcard removal
function remove_from_cpanel(string $domain): array {
    global $cpanelApiType, $cpanelUser, $USE_UAPI_FALLBACK;
    $results = [];
    $infoResp = cpanel_api_call("/execute/DomainInfo/list_domains");
    $isAddon = $isSub = $isParked = false;
    if ($infoResp['status'] === 'ok') {
        $d = $infoResp['data'] ?? [];
        $payload = $d['data'] ?? ($d['cpanelresult']['data'] ?? $d);
        if (is_array($payload)) {
            if (!empty($payload['addon_domains']) && in_array($domain, $payload['addon_domains'])) $isAddon = true;
            if (!empty($payload['sub_domains']) && in_array($domain, $payload['sub_domains'])) $isSub = true;
            if (!empty($payload['parked_domains']) && in_array($domain, $payload['parked_domains'])) $isParked = true;
        }
    } else {
        $results[] = "Info domains gagal: " . ($infoResp['msg'] ?? json_encode($infoResp));
    }

    if ($isAddon) {
        $tries = ["/execute/AddonDomain/deladdondomain?domain=".urlencode($domain), "/execute/AddonDomain/deleteaddondomain?domain=".urlencode($domain)];
        foreach ($tries as $p) {
            $r = cpanel_api_call($p);
            $results[] = "Addon try $p => ".($r['status']==='ok' ? json_encode($r['data']) : ($r['msg'] ?? $r['body'] ?? 'error'));
            if ($r['status']==='ok') return ['ok'=>true,'msgs'=>$results];
        }
    }
    if ($isSub) {
        $tries = ["/execute/SubDomain/delsubdomain?domain=".urlencode($domain)];
        foreach ($tries as $p) {
            $r = cpanel_api_call($p);
            $results[] = "Sub try $p => ".($r['status']==='ok' ? json_encode($r['data']) : ($r['msg'] ?? $r['body'] ?? 'error'));
            if ($r['status']==='ok') return ['ok'=>true,'msgs'=>$results];
        }
    }

    if ($isParked) {
        $tries = ["/execute/Park/unpark?domain=".urlencode($domain), "/execute/Park/unpark_domain?domain=".urlencode($domain)];
        foreach ($tries as $p) {
            $r = cpanel_api_call($p);
            $results[] = "Park try $p => ".($r['status']==='ok' ? json_encode($r['data']) : ($r['msg'] ?? $r['body'] ?? 'error'));
            $msg = $r['status']==='ok' ? json_encode($r['data']) : ($r['msg'] ?? $r['body'] ?? '');
            if (is_string($msg) && stripos($msg, 'Failed to load module') !== false && stripos($msg, 'Park') !== false) {
                log_action("cPanel Park module missing for $domain — attempting uapi/WHM and wildcard cleanup. Continuing.");
                if (!empty($USE_UAPI_FALLBACK)) {
                    $u = uapi_exec(['Park','unpark','domain='.$domain], $cpanelUser);
                    if ($u['status'] === 'ok') { log_action("uapi Park unpark succeeded for $domain"); break; }
                    log_action("uapi Park unpark failed for $domain: " . ($u['msg'] ?? 'no output'));
                    if (isset($u['msg']) && stripos($u['msg'],'setuids failed')!==false) {
                        // uapi not runnable as web user; it's a shared-hosting reality
                        log_action("uapi setuids failed for $domain (uapi needs root).");
                    }
                }
                if ($cpanelApiType === 'whm') {
                    $p2 = "/json-api/del_zone?domain=" . urlencode($domain);
                    $r2 = cpanel_api_call($p2);
                    $results[] = "Immediate WHM try $p2 => ".($r2['status']==='ok' ? json_encode($r2['data']) : ($r2['msg'] ?? $r2['body'] ?? 'error'));
                    if ($r2['status'] === 'ok') return ['ok'=>true,'msgs'=>$results];
                }
                break;
            }
            if ($r['status']==='ok') break;
        }
    }

    // Try wildcard removal via uapi if possible
    if (!empty($USE_UAPI_FALLBACK)) {
        $zoneFetch = uapi_fetch_zone_records($domain, $cpanelUser);
        if ($zoneFetch['status'] === 'ok' && is_array($zoneFetch['data'])) {
            $deletedAny = false;
            foreach ($zoneFetch['data'] as $rec) {
                $name = $rec['name'] ?? ($rec['record'] ?? null);
                $line = $rec['line'] ?? ($rec['record_line'] ?? null);
                if (!$name) continue;
                $needle1 = '*.' . $domain;
                $needle2 = '*.' . $domain . '.';
                if (strcasecmp($name, $needle1) === 0 || strcasecmp($name, $needle2) === 0 || (strpos($name, '*.') === 0 && stripos($name, $domain)!==false)) {
                    if ($line) {
                        $rem = uapi_remove_zone_record($domain, $line, $cpanelUser);
                        if ($rem['status'] === 'ok') {
                            log_action("uapi removed wildcard record line=$line for $domain");
                            $results[] = "Removed wildcard record (line $line) for $domain via uapi.";
                            $deletedAny = true;
                        } else {
                            log_action("uapi remove_zone_record failed for $domain line=$line: " . ($rem['msg'] ?? ''));
                        }
                    } else {
                        $type = $rec['type'] ?? null;
                        if ($type) {
                            $u = uapi_exec(['ZoneEdit','remove_zone_record','domain='.$domain,'name='.$name,'type='.$type], $cpanelUser);
                            if ($u['status'] === 'ok') {
                                log_action("uapi remove_zone_record by name succeeded for $name ($type) on $domain");
                                $results[] = "Removed wildcard $name ($type) via uapi.";
                                $deletedAny = true;
                            } else {
                                log_action("uapi remove by name failed for $name on $domain: " . ($u['msg'] ?? ''));
                            }
                        }
                    }
                }
            }
            if ($deletedAny) return ['ok'=>true,'msgs'=>$results];
            $results[] = "No wildcard removed via uapi for $domain.";
        } else {
            log_action("uapi fetch zone records failed for $domain: " . ($zoneFetch['msg'] ?? json_encode($zoneFetch['data'] ?? $zoneFetch['body'] ?? '')));
            $results[] = "uapi fetch zone records failed for $domain.";
        }
    } else {
        $results[] = "uapi fallback disabled.";
    }

    if ($cpanelApiType === 'whm') {
        $whmTry = "/json-api/del_zone?domain=" . urlencode($domain);
        $r = cpanel_api_call($whmTry);
        $results[] = "WHM fallback $whmTry => ".($r['status']==='ok' ? json_encode($r['data']) : ($r['msg'] ?? $r['body'] ?? 'error'));
        if ($r['status'] === 'ok') return ['ok'=>true,'msgs'=>$results];
    }

    $results[] = "Automated unpark/wildcard removal attempts finished for $domain (may require manual cleanup).";
    return ['ok'=>false,'msgs'=>$results];
}

// stub for provider-specific wildcard removal
function remove_wildcard_dns_stub(string $domain): bool { return true; }

// ---------------- MAIN ----------------
$csrf = generate_csrf();
$messages = [];
$manual_required = []; // domains that need human support

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    if (!verify_csrf($_POST['csrf'] ?? '')) {
        $messages[] = ['type'=>'error','text'=>'CSRF token invalid'];
    } else {
        $raw = (string)($_POST['domains'] ?? '');
        $lines = preg_split('/\r?\n/', $raw);
        $domains = sanitize_domains($lines);

        if (empty($domains)) {
            $messages[] = ['type'=>'error','text'=>'Tidak ada domain valid untuk dihapus.'];
        } else {
            $mysqli = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
            if ($mysqli->connect_errno) {
                $messages[] = ['type'=>'error','text'=>'Gagal konek DB: '.$mysqli->connect_error];
            } else {
                if (!table_exists($mysqli, $tableName)) {
                    $err = "Tabel '{$tableName}' tidak ditemukan di DB ".$mysqli->real_escape_string($mysqli->query("SELECT DATABASE()")->fetch_row()[0] ?? 'unknown');
                    log_action("ERROR: $err"); $messages[]=['type'=>'error','text'=>$err . ". Periksa nama tabel atau buat tabel tersebut."]; $mysqli->close();
                } else {
                    $backupFile = backup_table_rows($mysqli, $tableName, $domains);
                    if ($backupFile) { $messages[] = ['type'=>'info','text'=>"Backup dibuat: $backupFile"]; log_action("BACKUP: $backupFile table $tableName for ".implode(',', $domains)); }
                    else { $messages[] = ['type'=>'warn','text'=>'Backup mungkin kosong atau gagal. Periksa log.']; }

                    $placeholders = implode(',', array_fill(0, count($domains), '?'));
                    $types = str_repeat('s', count($domains));
                    $sql = "DELETE FROM `".$mysqli->real_escape_string($tableName)."` WHERE domain IN ($placeholders)";
                    $stmt = $mysqli->prepare($sql);
                    if (!$stmt) { $messages[]=['type'=>'error','text'=>'Gagal prepare statement: '.$mysqli->error]; log_action("ERROR prepare DELETE: ".$mysqli->error." -- SQL: $sql"); }
                    else {
                        if (!mysqli_bind_params($stmt, $types, $domains)) { $messages[]=['type'=>'error','text'=>'Gagal bind parameters.']; log_action("ERROR bind_param for DELETE"); }
                        else {
                            $ok = $stmt->execute();
                            if ($ok) { $affected = $stmt->affected_rows; $messages[]=['type'=>'success','text'=>"Baris di DB dihapus: $affected"]; log_action("DELETE DB table $tableName: ".implode(',', $domains)." (backup: $backupFile)"); }
                            else { $messages[]=['type'=>'error','text'=>'Gagal hapus DB: '.$stmt->error]; log_action("ERROR execute DELETE: ".$stmt->error); }
                        }
                        $stmt->close();
                    }

                    $dns_results = [];
                    foreach ($domains as $d) {
                        $cpRes = remove_from_cpanel($d);
                        if (!empty($cpRes['msgs'])) {
                            foreach ($cpRes['msgs'] as $m) log_action("cPanel: $m");
                            $visible = array_filter($cpRes['msgs'], function($m){ return !in_array($m, ['park_module_missing_continue','park_module_missing']); });
                            if (!empty($visible)) $messages[] = ['type'=>'info','text'=>"cPanel hasil untuk $d: ".htmlspecialchars(implode(' | ', $visible))];
                        }
                        if (empty($cpRes['ok']) || $cpRes['ok'] === false) $manual_required[] = $d;
                        $res = remove_wildcard_dns_stub($d);
                        $dns_results[$d] = $res ? 'ok' : 'failed';
                    }

                    if (!empty($manual_required)) {
                        $messages[] = ['type'=>'warn','text'=>'Beberapa domain memerlukan tindakan manual (hubungi hosting support): '.implode(', ', $manual_required)];
                    }
                    $messages[] = ['type'=>'info','text'=>'Hasil penghapusan wildcard DNS (stub): '.json_encode($dns_results)];

                    $mysqli->close();
                }
            }
        }
    }
}

// ---------------- UI ----------------
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Dashboard Hapus Multi Domain - AUTO (Unpark+Wildcard)</title>
<style>
body{font-family:Inter,Arial;margin:24px;background:#fafafa;color:#111}
.container{max-width:980px;margin:0 auto;background:#fff;padding:20px;border-radius:8px;box-shadow:0 6px 24px rgba(0,0,0,0.06)}
textarea{width:100%;height:180px;font-family:monospace;padding:8px}
.notice{padding:10px;border-radius:6px;margin-bottom:12px}
.notice.error{background:#fee;color:#900}
.notice.warn{background:#fff7e6;color:#8a5200}
.notice.info{background:#eef6ff;color:#064}
.notice.success{background:#e9ffef;color:#064}
.small{font-size:13px;color:#666}
.btn{display:inline-block;padding:8px 12px;border-radius:6px;border:0;background:#111;color:#fff;cursor:pointer}
.btn-danger{background:#b91c1c}
pre.log{max-height:240px;overflow:auto;background:#111;color:#fff;padding:8px;border-radius:6px;font-size:12px}
.manual{background:#fff4f4;border:1px solid #f0c0c0;padding:10px;border-radius:6px}
</style>
</head>
<body>
<div class="container">
  <h2>Dashboard Hapus Multi Domain + Wildcard (AUTO)</h2>
  <p class="small">Menggunakan table: <strong><?=htmlspecialchars($tableName)?></strong>. Tempel satu domain per baris.</p>

  <div class="notice info">AUTO_CONFIRM aktif — konfirmasi manual dihilangkan. Script akan coba unpark & hapus wildcard lewat uapi/WHM (jika tersedia).</div>

  <?php foreach ($messages as $m): ?>
    <div class="notice <?=htmlspecialchars($m['type'])?>"><?=htmlspecialchars($m['text'])?></div>
  <?php endforeach; ?>

  <form method="post">
    <input type="hidden" name="csrf" value="<?=htmlspecialchars($csrf)?>">
    <input type="hidden" name="action" value="delete">
    <label>Daftar domain (satu per baris):</label>
    <textarea name="domains" placeholder="example.com&#10;sub.example.com"></textarea>

    <p class="small">Sebelum hapus, sistem membuat backup JSON otomatis (ke folder backups/).</p>

    <div style="margin-top:12px">
      <button class="btn btn-danger" type="submit">Hapus Permanen</button>
      <span style="margin-left:12px" class="small">Aksi ini permanen. Pastikan backup berhasil.</span>
    </div>
  </form>

  <hr>
  <h4>Audit Log (20 terbaru)</h4>
  <pre class="log"><?php
    if (file_exists($logFile)) {
        $lines = array_slice(array_reverse(file($logFile, FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES)), 0, 20);
        echo implode("\n", array_reverse($lines));
    } else {
        echo "(belum ada log)";
    }
  ?></pre>

  <hr>
  <h4>Domains needing manual support</h4>
  <div class="manual">
    <?php
      if (!empty($manual_required)) {
          echo "<strong>Action required:</strong><br>" . htmlspecialchars(implode(', ', $manual_required));
          echo "<p class='small'>Please open a support ticket and ask them to run <code>/usr/local/cpanel/scripts/upcp</code> or run the shown uapi/WHM commands as root to unpark and remove wildcard DNS.</p>";
      } else {
          echo "None (automated attempts succeeded or none attempted yet).";
      }
    ?>
  </div>

</div>
</body>
</html>
