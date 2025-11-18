<?php
declare(strict_types=1);

/**
 * ajax_api_client.php — safer client for Smart Redirect API
 *
 * Gantikan skrip lama kamu. Fokus perbaikan:
 * - Parse JSON response dari API (ambil short_url), jangan pakai raw $resp.
 * - HMAC signature sesuai api.php (base64url tanpa padding, ksort key a–z).
 * - rawurlencode untuk shim l.facebook.com / l.instagram.com.
 * - Handling error rapi: HTTP code, curl error, API error.
 * - Tanpa arrow function; aman untuk PHP < 7.4.
 */

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['ok'=>false,'err'=>'method-not-allowed','allow'=>'POST']);
  exit;
}

/* ===== Input ===== */
$longurl  = isset($_POST['longurl']) ? trim((string)$_POST['longurl']) : '';
$prm      = isset($_POST['prm']) ? (string)$_POST['prm'] : '';
$langAPI  = isset($_POST['langAPI']) ? (string)$_POST['langAPI'] : '';
$text     = isset($_POST['status']) ? (string)$_POST['status'] : '';
$shimlink = isset($_POST['shimlink']) ? (string)$_POST['shimlink'] : '';

if ($longurl === '') {
  http_response_code(400);
  echo json_encode(['ok'=>false,'err'=>'missing longurl']);
  exit;
}

/* ===== Config HMAC ===== */
$AF_SECRET = getenv('AF_SECRET');
if (!$AF_SECRET || $AF_SECRET === '') {
  // fallback darurat; DI PRODUKSI PAKAI ENV/CONFIG
  $AF_SECRET = 'c4fa9b671fa72653b4458d038857421dfa2489f6a58b74d19a6c03392c023c4c';
}

/* ===== Signature ===== */
$ts = time();
$canon = [
  'code'  => '',
  'desc'  => '',
  'img'   => '',
  'title' => '',
  'ts'    => $ts,
  'url'   => $longurl,
];
ksort($canon);
$payload = json_encode($canon, JSON_UNESCAPED_SLASHES);
$sig = rtrim(strtr(base64_encode(hash_hmac('sha256', $payload, $AF_SECRET, true)), '+/', '-_'), '=');

/* ===== Call API ===== */
$api = 'https://me.ixg.llc/api.php?op=create';
$ch = curl_init($api);
curl_setopt_array($ch, [
  CURLOPT_POST => true,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_POSTFIELDS => http_build_query([
    'url'   => $canon['url'],
    'title' => $canon['title'],
    'desc'  => $canon['desc'],
    'img'   => $canon['img'],
    'code'  => $canon['code'],
    'ts'    => $ts,
    'sig'   => $sig,
  ]),
  CURLOPT_CONNECTTIMEOUT => 5,
  CURLOPT_TIMEOUT => 12,
]);
$resp = curl_exec($ch);
$cerr = curl_error($ch);
$http = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($resp === false) {
  http_response_code(502);
  echo json_encode(['ok'=>false,'err'=>'curl','msg'=>$cerr]);
  exit;
}

$j = json_decode($resp, true);
if (!is_array($j)) {
  http_response_code($http ?: 500);
  echo json_encode(['ok'=>false,'err'=>'bad-json','raw'=>$resp]);
  exit;
}
if (empty($j['ok'])) {
  http_response_code($http ?: 400);
  echo json_encode(['ok'=>false,'err'=>'api-failed','api'=>$j]);
  exit;
}

$short = (string)($j['short_url'] ?? '');
if ($short === '') {
  http_response_code(500);
  echo json_encode(['ok'=>false,'err'=>'no-short-url','api'=>$j]);
  exit;
}

/* ===== Build shim link ===== */
function rand_token($n) {
  $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
  $len = strlen($alphabet); $s='';
  for ($i=0; $i<$n; $i++) { $s .= $alphabet[random_int(0,$len-1)]; }
  return $s;
}

switch ($prm) {
  case 'b': // facebook l.php (desktop)
    $_short = 'https://l.facebook.com/l.php?u=' . rawurlencode($short)
            . '&h=' . rand_token(7) . '&s=1';
    break;
  case 'c': // warn gate
    $_short = 'https://web.facebook.com/flx/warn/?u=' . rawurlencode($short) . '&h=1';
    break;
  case 'd': // facebook l.php (m)
    $_short = 'https://p.facebook.com/l.php?u=' . rawurlencode($short)
            . '&h=' . rand_token(7) . '&s=1';
    break;
  case 'e': // instagram shim
    $_short = 'https://l.instagram.com/?u=' . rawurlencode($short)
            . '&e=' . rawurlencode($shimlink) . '&s=1';
    break;
  case 'f': // line wl
    $_short = 'https://l.wl.co/l?u=' . rawurlencode($short);
    break;
  default:
    $_short = $short;
}

/* ===== Template status ('@' diganti link) ===== */
if ($text === '{random}') {
  $choices = array(
    'Are you in the mood for some adult fun?',
    "I'm a very sexual woman looking for pleasure",
    'Cute and sexy female with curves in the right places looking for friends',
    "Hi guys, I'm sexy little kitty wants to play",
  );
  $pick = $choices[array_rand($choices)];
  $status = str_replace('#', $pick, '❤ # • Dm/Call me ♡ 𝑰𝒏𝒔𝒕𝒂𝒈𝒓𝒂𝒎 & -𝑾𝒉𝒂𝒕𝒔𝑨𝒑𝒑 ♡ @ ❤');
} elseif ($text === '{disable}') {
  $status = '';
} else {
  $status = (string)$text;
}

if ($status !== '') {
  $status = htmlspecialchars_decode($status, ENT_QUOTES);
  $l = str_replace('@', $_short, $status);
} else {
  $l = $_short;
}

$out = array(array('l' => $l));
echo json_encode($out, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
