<?php
// dashboard.php — random domain generator with global blacklist
// format: {word}-{word}-{number}.{tld}
// blocks substrings in built-in blacklist + custom blacklist input
// TLD manual input supported

// ---------------- CONFIG ----------------
define('DEFAULT_COUNT', 50);
define('MAX_COUNT', 1000);
define('MAX_TLDS', 200);
define('MAX_CUSTOM_BLACKLIST', 200);

$forbidden_core = 'netskope'; // core forbidden (keamanan ekstra)
$DEFAULT_TLDS = ['com','net','org','io','dev','xyz','app','co','site'];
// built-in brand blacklist (lowercase substrings)
$BUILTIN_BLACKLIST = [
    'google','facebook','meta','instagram','whatsapp','telegram','microsoft',
    'apple','amazon','paypal','stripe','netflix','tiktok','twitter','x','linkedin',
    'github','gitlab','bitbucket','dropbox','slack','zoom','adobe','salesforce',
    'oracle','sap','intel','amd','nvidia','uber','lyft','airbnb','booking',
    'netskope','crowdstrike','okta','auth0','cloudflare','akamai','yahoo',
    'bing','baidu','mozilla','samsung','sony','huawei','microsoftoffice','office',
    'visa','mastercard','amex','bank','banking'
];
// ----------------------------------------


/**
 * Parse user-provided TLD string into array of validated tlds.
 */
function parse_tlds($tlds_raw, $default_tlds) {
    if (!is_string($tlds_raw) || trim($tlds_raw) === '') return $default_tlds;
    $parts = preg_split('/\s*,\s*/', strtolower(trim($tlds_raw)), -1, PREG_SPLIT_NO_EMPTY);
    $arr = [];
    foreach ($parts as $p) {
        $p = preg_replace('/[^a-z0-9\-]/', '', $p);
        if ($p === '') continue;
        if (strlen($p) < 2 || strlen($p) > 63) continue;
        if ($p[0] === '-' || substr($p, -1) === '-') continue;
        $arr[] = $p;
        if (count($arr) >= MAX_TLDS) break;
    }
    return count($arr) ? array_values(array_unique($arr)) : $default_tlds;
}

/**
 * Parse custom blacklist (comma-separated) into array of lowercase substrings.
 */
function parse_custom_blacklist($raw) {
    if (!is_string($raw) || trim($raw) === '') return [];
    $parts = preg_split('/\s*,\s*/', strtolower(trim($raw)), -1, PREG_SPLIT_NO_EMPTY);
    $arr = [];
    foreach ($parts as $p) {
        $p = preg_replace('/[^a-z0-9\-]/', '', $p);
        if ($p === '') continue;
        $arr[] = $p;
        if (count($arr) >= MAX_CUSTOM_BLACKLIST) break;
    }
    return array_values(array_unique($arr));
}

/**
 * Check a candidate domain for any blacklist substring (case-insensitive).
 * Returns true if domain is clean (i.e., no forbidden substrings).
 */
function domain_is_clean($domain, $blacklist) {
    $domain_lc = strtolower($domain);
    foreach ($blacklist as $b) {
        if ($b === '') continue;
        if (stripos($domain_lc, $b) !== false) return false;
    }
    return true;
}

/**
 * Generate random domain in format: word-word-number.tld
 */
function generate_domain($blacklist, $tlds) {
    $words = [
        'alpha','bravo','charlie','delta','echo','foxtrot','golf','hotel','india',
        'juliet','kilo','lima','mike','november','oscar','papa','quebec','romeo',
        'sierra','tango','uniform','victor','whiskey','xray','yankee','zulu',
        'astra','neon','cyber','nova','terra','lumen','vector','omega','nexus','aurora',
        'vortex','stellar','hyper','quantum','proto','zenith','chrono','aero','draco','lyra'
    ];
    // fallback tld
    if (!is_array($tlds) || count($tlds) === 0) $tlds = ['com'];
    for ($i=0;$i<200;$i++) {
        $n1 = $words[array_rand($words)];
        $n2 = $words[array_rand($words)];
        while ($n2 === $n1) $n2 = $words[array_rand($words)];
        $num = rand(1,9999);
        $tld = $tlds[array_rand($tlds)];
        $domain = "{$n1}-{$n2}-{$num}.{$tld}";
        // always disallow core forbidden even if not in blacklist list
        $all_blacklist = $blacklist;
        if (!in_array(strtolower($GLOBALS['forbidden_core']), $all_blacklist)) $all_blacklist[] = strtolower($GLOBALS['forbidden_core']);
        if (domain_is_clean($domain, $all_blacklist)) return strtolower($domain);
    }
    return 'safe-random-1.com';
}

// ---------- API MODE ----------
if (isset($_GET['api'])) {
    header('Content-Type: application/json; charset=utf-8');

    $count = isset($_GET['count']) ? intval($_GET['count']) : DEFAULT_COUNT;
    if ($count < 1) $count = DEFAULT_COUNT;
    if ($count > MAX_COUNT) $count = MAX_COUNT;

    $tlds = parse_tlds($_GET['tlds'] ?? '', $DEFAULT_TLDS);

    // merge built-in + custom blacklist
    $custom_raw = $_GET['custom_blacklist'] ?? '';
    $custom = parse_custom_blacklist($custom_raw);
    $blacklist = array_values(array_unique(array_merge(array_map('strtolower',$BUILTIN_BLACKLIST), $custom)));

    $domains = [];
    $tries = 0;
    while (count($domains) < $count && $tries < $count * 50) {
        $d = generate_domain($blacklist, $tlds);
        if (isset($domains[$d])) { $tries++; continue; }
        // final safety ensure
        if (!domain_is_clean($d, $blacklist)) { $tries++; continue; }
        $domains[$d] = true;
        $tries++;
    }

    echo json_encode([
        'status'=>'ok',
        'count'=>count($domains),
        'requested'=>intval($_GET['count'] ?? DEFAULT_COUNT),
        'tlds'=>$tlds,
        'blacklist'=>array_values($blacklist),
        'domains'=>array_values(array_keys($domains))
    ], JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
    exit;
}

// ---------- Download blacklist CSV ----------
if (isset($_GET['download_blacklist'])) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=blacklist.csv');
    $custom_raw = $_GET['custom_blacklist'] ?? '';
    $custom = parse_custom_blacklist($custom_raw);
    $blacklist = array_values(array_unique(array_merge(array_map('strtolower',$BUILTIN_BLACKLIST), $custom)));
    echo "blacklist\n";
    foreach ($blacklist as $b) echo $b . "\n";
    exit;
}

// ----------------- UI -----------------
?><!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Dashboard Random Domain — with Global Blacklist</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap.min.css" rel="stylesheet">
<style>
body{padding:20px;font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,monospace;}
textarea#tlds, textarea#custom_blacklist { min-height:60px; resize:vertical; }
.small-muted{color:#777;}
.badge-black{background:#c0392b;color:#fff;}
pre.blacklist{background:#f7f7f7;padding:10px;border-radius:4px;max-height:160px;overflow:auto;}
</style>
</head>
<body>
<div class="container-fluid">
    <h3>Random Domain Generator <small class="small-muted">format: <code>kata-kata-angka.tld</code> — blacklist aktif</small></h3>

    <div class="row" style="margin-top:12px;">
        <div class="col-sm-2">
            <label>Count</label>
            <input id="count" type="number" min="1" max="<?php echo MAX_COUNT ?>" value="<?php echo DEFAULT_COUNT ?>" class="form-control">
        </div>

        <div class="col-sm-4">
            <label>TLDs (pisah koma)</label>
            <textarea id="tlds" class="form-control"><?php echo implode(',', $DEFAULT_TLDS) ?></textarea>
            <span class="help-block small-muted">Contoh: <code>com,io,dev</code></span>
        </div>

        <div class="col-sm-6">
            <label>Custom blacklist (opsional, pisah koma)</label>
            <textarea id="custom_blacklist" class="form-control" placeholder="masukkan kata yg ingin diblok, mis: evil,badbrand"></textarea>
            <div style="margin-top:6px;">
                <button id="btn-refresh" class="btn btn-primary">Generate</button>
                <button id="btn-csv" class="btn btn-default">Download CSV</button>
                <button id="btn-copy" class="btn btn-default">Copy</button>
                <a id="btn-download-blacklist" class="btn btn-warning" href="#" role="button">Download Blacklist CSV</a>
            </div>
        </div>
    </div>

    <hr>

    <div class="row" style="margin-bottom:12px;">
        <div class="col-sm-12">
            <table id="tbl" class="table table-striped table-bordered" style="width:100%">
                <thead><tr><th style="width:60px">#</th><th>Domain</th></tr></thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <h5>Built-in blacklist (sample)</h5>
            <pre class="blacklist"><?php echo implode(", ", array_slice($BUILTIN_BLACKLIST,0,40)) . (count($BUILTIN_BLACKLIST) > 40 ? "\n...(".(count($BUILTIN_BLACKLIST)-40)." more)" : ""); ?></pre>
        </div>
        <div class="col-sm-6">
            <h5>Notes</h5>
            <ul>
                <li>Generator akan menolak domain yang mengandung substring blacklist (case-insensitive).</li>
                <li>Core forbidden term <code><?php echo htmlspecialchars($forbidden_core) ?></code> selalu diblok.</li>
                <li>Custom blacklist bersifat temporary (hanya untuk request saat ini), tidak disimpan server.</li>
            </ul>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap.min.js"></script>
<script>
function fetchDomains(count,tlds,custom_blacklist){
    return $.getJSON('?api=1&count='+encodeURIComponent(count)
        +'&tlds='+encodeURIComponent(tlds)
        +'&custom_blacklist='+encodeURIComponent(custom_blacklist));
}
function renderTable(domains){
    const tbody = $('#tbl tbody').empty();
    domains.forEach((d,i)=>tbody.append(`<tr><td>${i+1}</td><td>${d}</td></tr>`));
    if ($.fn.dataTable.isDataTable('#tbl')) $('#tbl').DataTable().destroy();
    $('#tbl').DataTable({pageLength:25,lengthMenu:[10,25,50,100]});
}
function downloadCSV(rows){
    const csv = rows.join("\r\n");
    const blob = new Blob([csv], {type:'text/csv'});
    const a = document.createElement('a');
    a.href = URL.createObjectURL(blob);
    a.download = 'domains-'+Date.now()+'.csv';
    document.body.appendChild(a);
    a.click();
    setTimeout(()=>{ URL.revokeObjectURL(a.href); a.remove(); },500);
}

$(function(){
    function load(){
        const count = parseInt($('#count').val()) || <?php echo DEFAULT_COUNT ?>;
        const tlds = $('#tlds').val() || '';
        const custom = $('#custom_blacklist').val() || '';
        $('#btn-refresh').prop('disabled', true).text('Loading...');
        fetchDomains(count,tlds,custom).done(function(resp){
            if (resp && resp.status === 'ok') {
                renderTable(resp.domains);
                // update download blacklist link with current custom param
                $('#btn-download-blacklist').attr('href','?download_blacklist=1&custom_blacklist='+encodeURIComponent(custom));
            } else {
                alert('Gagal generate domain');
            }
        }).fail(function(){
            alert('Request gagal');
        }).always(function(){
            $('#btn-refresh').prop('disabled', false).text('Generate');
        });
    }

    $('#btn-refresh').on('click', load);

    $('#btn-csv').on('click', function(){
        const rows = $('#tbl tbody tr td:nth-child(2)').map((_,td)=>$(td).text()).get();
        if (rows.length === 0) { alert('Tidak ada data'); return; }
        downloadCSV(rows);
    });

    $('#btn-copy').on('click', function(){
        const rows = $('#tbl tbody tr td:nth-child(2)').map((_,td)=>$(td).text()).get();
        if (rows.length === 0) { alert('Tidak ada data'); return; }
        navigator.clipboard?.writeText(rows.join('\n')).then(()=> alert('Copied'), ()=> alert('Copy failed'));
    });

    // initial load
    load();
});
</script>
</body>
</html>
