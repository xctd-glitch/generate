<?php
error_reporting(0);
include_once('password.login.php');

$LOGIN_INFORMATION = array(
  '' => ADMIN_PASSWORD
);
if(isset($_GET['help'])) {
  die('Include following code into every page you would like to protect, at the very beginning (first line):<br>&lt;?php include("' . str_replace('\\','\\\\',__FILE__) . '"); ?&gt;');
}?>
    <!DOCTYPE html>
    <html>
    <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        
        <title>ADMIN PANEL</title>
        <link href="/favicon.ico" rel="icon" type="image/x-icon" />
        <link rel="stylesheet" href="../dist/bootstrap.min.css" type="text/css" media="all">
        <link href="../dist/flags.css" rel="stylesheet" />
        <link href="../dist/jquery.bootgrid.css" rel="stylesheet" />
        <script src="../dist/jquery-1.11.1.min.js"></script>
        <script src="../dist/bootstrap.min.js"></script>
        <script src="../dist/jquery.bootgrid.min.js"></script>
        <link href="//fonts.googleapis.com/css?family=Open+Sans:400,600" rel="stylesheet" type="text/css">
        <link href="//use.fontawesome.com/releases/v5.7.0/css/all.css" rel="stylesheet" type="text/css">
        <script src="//unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@8"></script>
        <style>
        body {
            padding-top: 70px;font-family:consolas;
        }
        </style>
    </head>
    <body>
        <?php 
	    $timeout = (TIMEOUT_MINUTES == 0 ? 0 : time() + TIMEOUT_MINUTES * 1);
	    if(isset($_GET['logout'])) {
	      setcookie("verify", '', $timeout, '/'); 
	      header('Location: ' . LOGOUT_URL);
	      exit();
	    }
	    if(!function_exists('showLoginPasswordProtect')) {

	    function showLoginPasswordProtect($error_msg) {
	    ?>
            <div class="container" style="width:600px; margin-top: -30px;" style="overflow: hidden">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <button type="button" class="btn btn-xs btn-primary" data-row-id="0">
                            <span class="glyphicon glyphicon-user"></span><strong> Login: Admin Panel</strong></button>
                    </div>
                    <div class="panel-body">
                        <?php if($error_msg == "err"){
                         $err = 'Access denied. Your IP address! '. $_SERVER['REMOTE_ADDR'];
                         echo "
                         <script type=\"text/javascript\">
                         swal.fire({
                         position: 'top-end',
                         type: 'error',
                         title: '$err',
                         showConfirmButton: false,
                         timer: 750
                         });</script>";
                         }?>
                            <form method="post">
                                <input class="form-control input-sm" name="access_login" placeholder="Username" type="hidden" value="">
                                <div class="input-group">
                                    <input type="password" class="form-control input-sm" id="access_password" name="access_password" autofocus="" placeholder="{password}">
                                    <div class="input-group-btn">
                                        <button class="btn btn-default btn-sm" type="submit" type="button"><span class="fa fa-spinner fa-pulse fa-fw"></span><strong> Login</strong></button>
                                    </div>
                                </div>
                            </form>
                    </div>
                </div>
            </div>
            <?php
	      die();
	    }
	    }

	    if (isset($_POST['access_password'])) {

	      $login = isset($_POST['access_login']) ? $_POST['access_login'] : '';
	      $pass = $_POST['access_password'];
	      if (!USE_USERNAME && !in_array($pass, $LOGIN_INFORMATION)
	      || (USE_USERNAME && ( !array_key_exists($login, $LOGIN_INFORMATION) || $LOGIN_INFORMATION[$login] != $pass ) ) 
	      ) {
	       // showLoginPasswordProtect("Access denied. Your IP address ".$_SERVER['REMOTE_ADDR'].".");
	        showLoginPasswordProtect("err");
	      }
	      else {
	        setcookie("verify", md5($login.'%'.$pass), $timeout, '/');

	        unset($_POST['access_login']);
	        unset($_POST['access_password']);
	        unset($_POST['Submit']);
	      }

	    }

	    else {
	      if (!isset($_COOKIE['verify'])) {
	        showLoginPasswordProtect("");
	      }
	      $found = false;
	      foreach($LOGIN_INFORMATION as $key=>$val) {
	        $lp = (USE_USERNAME ? $key : '') .'%'.$val;
	        if ($_COOKIE['verify'] == md5($lp)) {
	          $found = true;
	          // prolong timeout
	          if (TIMEOUT_CHECK_ACTIVITY) {
	            setcookie("verify", md5($lp), $timeout, '/');
	          }
	          break;
	        }
	      }
	      if (!$found) {
	        showLoginPasswordProtect("");
	      }
	    }
?>