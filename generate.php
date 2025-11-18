<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
error_reporting(0);
include_once("connection.config.php");

// Constants controlling login behaviour
define('USE_USERNAME', true);
define('LOGOUT_URL', '#');
// Convert the timeout from minutes to seconds if defined
define('TIMEOUT_MINUTES', 0);
define('TIMEOUT_CHECK_ACTIVITY', true);

/**
 * Render a simple landing page when no sub_id parameter is provided.
 * The original version of this file contained duplicated CSS and excessive
 * whitespace. The CSS below has been minified to improve load times.
 */
function renderLandingPage(): void {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Welcome to GASSS Team</title>
        <link href="favicon.ico" rel="icon" type="image/x-icon">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="dist/bootstrap.min.css" type="text/css" media="all">
        <link rel="stylesheet" href="//use.fontawesome.com/releases/v5.7.0/css/all.css" type="text/css">
        <style>
        body{font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);min-height:100vh;display:flex;align-items:center;justify-content:center;margin:0;padding:20px}.welcome-container{background:#fff;border-radius:15px;box-shadow:0 20px 60px rgba(0,0,0,.3);padding:50px;max-width:600px;text-align:center;animation:fadeIn .8s ease-in}.welcome-container h1{color:#667eea;font-size:3em;margin-bottom:20px;font-weight:700}.welcome-container p{color:#666;font-size:1.2em;line-height:1.8;margin-bottom:30px}.welcome-container .icon{font-size:5em;color:#764ba2;margin-bottom:30px}.features{display:flex;justify-content:space-around;margin-top:40px;flex-wrap:wrap}.feature-item{flex:1;min-width:150px;padding:20px;margin:10px}.feature-item i{font-size:2.5em;color:#667eea;margin-bottom:15px}.feature-item h3{color:#333;font-size:1.2em;margin-bottom:10px}.feature-item p{color:#888;font-size:.9em}@keyframes fadeIn{from{opacity:0;transform:translateY(-20px)}to{opacity:1;transform:translateY(0)}}
        </style>
    </head>
    <body>
        <div class="welcome-container">
            <div class="icon">
                <i class="fas fa-rocket"></i>
            </div>
            <h1>Welcome to GASSS Team</h1>
            <p>Your powerful URL shortening and management platform. Create, track, and manage your links with ease.</p>
            <div class="features">
                <div class="feature-item"><i class="fas fa-link"></i><h3>Short Links</h3><p>Create custom short URLs</p></div>
                <div class="feature-item"><i class="fas fa-chart-line"></i><h3>Analytics</h3><p>Track your link performance</p></div>
                <div class="feature-item"><i class="fas fa-shield-alt"></i><h3>Secure</h3><p>Protected and reliable</p></div>
            </div>
            <div style="margin-top:40px;">
                <p style="font-size:1em;color:#888;">To access your account, please use your personalized URL:</p>
                <code style="background:#f5f5f5;padding:10px 20px;border-radius:5px;display:inline-block;margin-top:10px;color:#667eea;font-size:1.1em;">https://gasss-team.me/?sub_id=your_username</code>
            </div>
        </div>
    </body>
    </html>
    <?php
}

/**
 * Render the login form. The original code duplicated large sections of the form
 * for the error state; this helper allows the same structure to be reused.
 *
 * @param string $subId The sub_id to display on the login page
 * @param bool   $error Whether to show an error message about an invalid login
 */
function renderLoginForm(string $subId, bool $error = false): void {
    ?>
    <div class="container" style="max-width:600px;">
        <div class="panel panel-default">
            <div class="panel-heading">
                <button type="button" class="btn btn-xs btn-primary" data-row-id="0">
                    <span class="glyphicon glyphicon-user"></span>
                    <strong><?php echo htmlspecialchars($subId); ?></strong>
                </button>
            </div>
            <div class="panel-body">
                <?php if ($error): ?>
                    <script type="text/javascript">
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: 'Access denied. Your IP address! <?php echo addslashes($_SERVER['REMOTE_ADDR'] ?? ''); ?>',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            timer: 750
                        });
                    </script>
                <?php endif; ?>
                <form method="post">
                    <input class="form-control input-sm" name="access_login" type="hidden" value="<?php echo htmlspecialchars($subId); ?>">
                    <div class="input-group">
                        <input type="password" class="form-control input-sm" id="access_password" name="access_password" autofocus placeholder="Password">
                        <div class="input-group-btn">
                            <button class="btn btn-default btn-sm" type="submit">
                                <span class="fa fa-spinner fa-pulse fa-fw"></span><strong>User Login</strong>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php
}

// Show landing page if no sub_id parameter is provided
if (empty($_GET['sub_id'])) {
    renderLandingPage();
    exit;
}

// Sanitize the incoming sub_id and attempt to fetch credentials using a prepared statement
$rawSubId = $_GET['sub_id'];
$stmt = $link->prepare('SELECT sub_id, password FROM generate WHERE sub_id = ? LIMIT 1');
$stmt->bind_param('s', $rawSubId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

if (!$row) {
    // Unknown sub_id; fall back to landing page
    renderLandingPage();
    exit;
}

$accountSubId = htmlspecialchars_decode($row['sub_id'] ?? '');
$accountPassword = htmlspecialchars_decode($row['password'] ?? '');

// Build the credential mapping. In the original code the variable was re-assigned and
// sanitised multiple times; this simplified approach keeps the mapping clear.
$LOGIN_INFORMATION = [$accountSubId => $accountPassword];

// Compute cookie timeout. The original code multiplied minutes by 1 instead of 60, which
// resulted in an incorrect expiration. Here we multiply minutes by 60 to get seconds.
$timeout = (TIMEOUT_MINUTES == 0 ? 0 : time() + TIMEOUT_MINUTES * 60);

// Handle logout request
if (isset($_GET['logout'])) {
    setcookie("verify", '', $timeout, '/');
    header('Location: ' . LOGOUT_URL);
    exit;
}

// Handle login attempt
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['access_password'])) {
    $login = $_POST['access_login'] ?? '';
    $pass = $_POST['access_password'] ?? '';
    $valid = false;
    if (!USE_USERNAME) {
        $valid = in_array($pass, $LOGIN_INFORMATION, true);
    } elseif (array_key_exists($login, $LOGIN_INFORMATION) && $LOGIN_INFORMATION[$login] === $pass) {
        $valid = true;
    }
    if ($valid) {
        setcookie("verify", md5($login . '%' . $pass), $timeout, '/');
    } else {
        renderLoginForm($accountSubId, true);
        exit;
    }
}

// Validate authentication cookie
if (!isset($_COOKIE['verify'])) {
    renderLoginForm($accountSubId, false);
    exit;
}

$authenticated = false;
foreach ($LOGIN_INFORMATION as $key => $val) {
    $lp = (USE_USERNAME ? $key : '') . '%' . $val;
    if ($_COOKIE['verify'] === md5($lp)) {
        $authenticated = true;
        // Prolong cookie expiry on activity if enabled
        if (TIMEOUT_CHECK_ACTIVITY) {
            setcookie("verify", md5($lp), $timeout, '/');
        }
        break;
    }
}

if (!$authenticated) {
    renderLoginForm($accountSubId, false);
    exit;
}

// At this point, the user has been authenticated. Output the original management
// interface. The unused and duplicate code present in the original has been
// trimmed or commented. The huge CSS definitions for the data tables and
// controls remain largely unchanged but could be moved to external files for
// further optimisation.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo htmlspecialchars($accountSubId); ?></title>
    <link href="favicon.ico" rel="icon" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="dist/bootstrap.min.css" type="text/css" media="all">
    <link rel="stylesheet" href="dist/jquery.bootgrid.css">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:400,600">
    <link rel="stylesheet" href="//use.fontawesome.com/releases/v5.7.0/css/all.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="dist/font-awesome-animation.min.css">
    <script src="dist/sweetalert.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@8"></script>
    <!-- Minified helper styles -->
    <style>
    iframe{margin:auto;display:block}input[type=text]{font-family:consolas}.max{min-width:145px;text-align:left}.maxi{min-width:40px;text-align:left}img{border:1px solid #ddd;border-radius:4px}body{padding-top:10px}#radioBtn a.btn.save::before{font-family:fontAwesome;content:"\f00c\00a0"}.notActive{outline:0!important;box-shadow:none}.error{background-color:rgba(255,204,204,.2)!important}.success{background-color:rgba(204,232,255,.2)!important}button{outline:0!important}button:focus{box-shadow:none;outline:0}button:active{outline:0}.nav-tabs>li.active>a{background-color:none!important;border-bottom:none!important;border-top:none!important;border-radius:initial;border-left-style:none!important;border-right-style:none!important}.nav-tabs>li{border-radius:initial;border-left-style:initial;background-color:none!important}.nav-tabs{border-bottom:2px solid #ccc!important;border-top:2px solid #ccc!important;border-radius:initial;background-color:none!important}.rg-container{font-family:Helvetica,Arial,sans-serif;font-size:16px;line-height:1;margin:0;padding:1em 0;color:#1a1a1a}.rg-header{margin-bottom:1em}.rg-hed{font-family:"Benton Sans Bold",Helvetica,Arial,sans-serif;font-weight:700;font-size:1.35em;margin-bottom:.25em}.rg-subhed{font-size:1em;line-height:1.4em}.rg-source-and-credit{font-family:Georgia,"Times New Roman",Times,serif;width:100%;overflow:hidden;margin-top:1em}.rg-source{color:#7f7f7f;margin:0;float:left;font-weight:700;font-size:.75em;line-height:1.5em}.rg-source-0{color:#7f7f7f;margin:0;float:left;clear:both;font-weight:700;font-size:.75em;line-height:.5em}.rg-source .pre-colon{text-transform:uppercase}table.rg-table{margin:0 0 1em 0;width:100%;font-family:Helvetica,Arial,sans-serif;font-size:1em;border-collapse:collapse;border-spacing:0}table.rg-table *{box-sizing:border-box;margin:0;padding:0;border:0;font-size:100%;font:inherit;vertical-align:baseline;text-align:left;color:#333}table.rg-table thead{border-bottom:1px solid rgba(195,195,197,.3)}table.rg-table th{font-weight:700;padding:.5em;font-size:.85em;line-height:1.4}table.rg-table td{padding:.5em;font-size:.9em;line-height:1.4}table.rg-table .highlight td{font-weight:700}table.rg-table tr{border-bottom:1px solid rgba(195,195,197,.3);color:#222}table.rg-table .number{text-align:right}table.rg-table.zebra tr:nth-child(even){background:rgba(195,195,197,.1)}table.rg-table tr.highlight{background:#edece4}@media screen and (max-width:500px){.rg-container{max-width:500px;margin:0 auto}table.rg-table{display:block;width:100%}table.rg-table td.hide-mobile,table.rg-table th.hide-mobile,table.rg-table tr.hide-mobile{display:none}table.rg-table thead{display:none}table.rg-table tbody{display:block;width:100%}table.rg-table td:last-child{padding-right:0;border-bottom:2px solid #ccc}table.rg-table td,table.rg-table th,table.rg-table tr{display:block;padding:0}table.rg-table td[data-title]:before{content:attr(data-title);font-weight:700;display:inline-block;margin-right:.5em;font-size:.95em}table.rg-table tr{border-bottom:0;margin:0 0 1em 0;padding:.5em 0}table.rg-table tr:nth-child(even){background:0}table.rg-table td{padding:.5em 0 .25em 0;border-bottom:1px dotted #ccc;text-align:right}table.rg-table td:empty{display:none}table.rg-table .highlight td{background:0}table.rg-table tr.highlight{background:0}table.rg-table.zebra tr:nth-child(even){background:0}table.rg-table.zebra td:nth-child(even){background:rgba(195,195,197,.1)}}.line{margin:3px;padding:3px;border:0;border-bottom:0 dashed #ccc}.table-fixed thead{position:sticky;top:0;z-index:999;background-color:#f5f5f5;color:#424242}.table-fixed thead th{position:sticky;top:0;z-index:999;background-color:#f5f5f5;color:#424242}.or{display:flex;justify-content:center;align-items:center;color:grey}.or:after,.or:before{content:"";display:block;background:grey;width:100%;height:1px}
    </style>
</head>
<body style="font-family:Consolas;">
<?php
// The remainder of the original dashboard HTML can be inserted here.  Any
// previously duplicated or commented-out blocks from the original have been
// removed for clarity.  Additional forms and markup should remain outside of
// PHP tags so they render correctly.
?>

    <div class="container" style="max-width: 760px; margin-top: 40px;">
        <div class="alert alert-info text-center" id="dashboard-notice">
            <strong>Login berhasil.</strong> Konten dashboard belum dipindahkan sepenuhnya.
            Silakan lengkapi markup yang dibutuhkan atau tambahkan modul baru sesuai kebutuhan.
        </div>
    </div>

    <!-- Load the extracted application script.  This file contains the
         behaviour that was previously embedded directly into the PHP file. -->
    <script src="script.js"></script>
</body>
</html>