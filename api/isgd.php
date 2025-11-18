<?php
declare(strict_types=1);

/**
 * ajax_api_isgd.php — fixed is.gd client + shim builder
 *
 * Perbaikan utama:
 * - URL-encode benar saat call is.gd (rawurlencode), bukan tempel mentah.
 * - Hapus CURLOPT_SSL_VERIFYPEER=false. Pakai timeout & error handling bener.
 * - Trim output is.gd dan deteksi error ("Error:").
 * - Gunakan rawurlencode untuk semua shim (l.php, warn, l.instagram.com, dll).
 * - {random} & {disable} dibenahi. {disable} = pakai link saja, tanpa teks.
 * - Placeholder '@' di-merge setelah aman.
 * - Output JSON rapi; tanpa echo campur return.
 * - Aman untuk PHP < 7.4 (tanpa arrow function).
 */

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok'=>false,'err'=>'method-not-allowed','allow'=>'POST']);
    exit;
}

if (!isset($_POST['longurl']) || trim((string)$_POST['longurl']) === '') {
    http_response_code(400);
    echo json_encode(['ok'=>false,'err'=>'missing longurl']);
    exit;
}

$longurl  = trim((string)$_POST['longurl']);
$prm      = isset($_POST['prm']) ? (string)$_POST['prm'] : '';
$langAPI  = isset($_POST['langAPI']) ? (string)$_POST['langAPI'] : '';
$text     = isset($_POST['status']) ? (string)$_POST['status'] : '';
$shimlink = isset($_POST['shimlink']) ? (string)$_POST['shimlink'] : '';

/* ===== Validate longurl ===== */
function is_http_url($u) {
    if (!filter_var($u, FILTER_VALIDATE_URL)) return false;
    $sch = strtolower((string)parse_url($u, PHP_URL_SCHEME));
    return $sch === 'http' || $sch === 'https';
}
if (!is_http_url($longurl)) {
    http_response_code(400);
    echo json_encode(['ok'=>false,'err'=>'invalid longurl']);
    exit;
}

/* ===== Shorten via is.gd ===== */
function shorten_isgd($url) {
    $api = 'https://is.gd/create.php?format=simple&url=' . rawurlencode($url);
    $ch = curl_init($api);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER => 0,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_TIMEOUT => 12,
        CURLOPT_SSL_VERIFYPEER => true,
    ]);
    $resp = curl_exec($ch);
    $cerr = curl_error($ch);
    $http = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($resp === false) {
        return [null, 'curl:'.$cerr];
    }
    $s = trim((string)$resp);
    if ($http !== 200) {
        return [null, 'http:'.$http.' body:'.$s];
    }
    if ($s === '' || stripos($s, 'Error:') === 0) {
        return [null, 'isgd:'.$s];
    }
    return [$s, null];
}

list($short, $err) = shorten_isgd($longurl);
if ($short === null) {
    http_response_code(502);
    echo json_encode(['ok'=>false,'err'=>'shorten_failed','msg'=>$err]);
    exit;
}

/* ===== Random token ===== */
function rand_token($n) {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $len = strlen($alphabet); $s = '';
    for ($i=0; $i<$n; $i++) { $s .= $alphabet[random_int(0, $len-1)]; }
    return $s;
}

/* ===== Build shim ===== */
$shim = $short; // default tanpa shim
switch ($prm) {
    case 'b': // facebook l.php desktop
        $shim = 'https://l.facebook.com/l.php?u=' . rawurlencode($short) . '&h=' . rand_token(7) . '&s=1';
        break;
    case 'c': // facebook warn gate
        $shim = 'https://web.facebook.com/flx/warn/?u=' . rawurlencode($short) . '&h=1';
        break;
    case 'd': // facebook l.php mobile
        $shim = 'https://p.facebook.com/l.php?u=' . rawurlencode($short) . '&h=' . rand_token(7) . '&s=1';
        break;
    case 'e': // instagram shim
        $shim = 'https://l.instagram.com/?u=' . rawurlencode($short) . '&e=' . rawurlencode($shimlink) . '&s=1';
        break;
    case 'f': // line wl
        $shim = 'https://l.wl.co/l?u=' . rawurlencode($short);
        break;
}

/* ===== Status template ===== */
function pick_status($text) {
    if ($text === '{random}') {
        $choices = array(
            'Are you in the mood for some adult fun?',
            "I'm a very sexual woman looking for pleasure",
            'Cute and sexy female with curves in the right places looking for friends',
            "Hi guys, I'm sexy little kitty wants to play",
        );
        $pick = $choices[array_rand($choices)];
        return str_replace('#', $pick, '❤ # • Dm/Call me ♡ 𝑰𝒏𝒔𝒕𝒂𝒈𝒓𝒂𝒎 & -𝑾𝒉𝒂𝒕𝒔𝑨𝒑𝒑 ♡ @ ❤');
    }
    if ($text === '{disable}') return '';
    return (string)$text;
}

$status = pick_status($text);
if ($status !== '') {
    $status = htmlspecialchars_decode($status, ENT_QUOTES);
    $final = str_replace('@', $shim, $status);
} else {
    $final = $shim;
}

$out = array(array('l' => $final));
echo json_encode($out, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
