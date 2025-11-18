<?php 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

  error_reporting(0);
  include_once("connection.config.php");

   define('USE_USERNAME', true);
   define('LOGOUT_URL', '#');
   define('TIMEOUT_MINUTES', 0);
   define('TIMEOUT_CHECK_ACTIVITY', true);

// Check if no sub_id parameter - show landing page
if(!isset($_GET['sub_id']) || empty($_GET['sub_id'])){
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8">
    
    <title>Welcome to GASSS Team</title>
    <link href="favicon.ico" rel="icon" type="image/x-icon" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="dist/bootstrap.min.css" type="text/css" media="all">
    <link href="//use.fontawesome.com/releases/v5.7.0/css/all.css" rel="stylesheet" type="text/css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }
        .welcome-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 50px;
            max-width: 600px;
            text-align: center;
            animation: fadeIn 0.8s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .welcome-container h1 {
            color: #667eea;
            font-size: 3em;
            margin-bottom: 20px;
            font-weight: 700;
        }
        .welcome-container p {
            color: #666;
            font-size: 1.2em;
            line-height: 1.8;
            margin-bottom: 30px;
        }
        .welcome-container .icon {
            font-size: 5em;
            color: #764ba2;
            margin-bottom: 30px;
        }
        .features {
            display: flex;
            justify-content: space-around;
            margin-top: 40px;
            flex-wrap: wrap;
        }
        .feature-item {
            flex: 1;
            min-width: 150px;
            padding: 20px;
            margin: 10px;
        }
        .feature-item i {
            font-size: 2.5em;
            color: #667eea;
            margin-bottom: 15px;
        }
        .feature-item h3 {
            color: #333;
            font-size: 1.2em;
            margin-bottom: 10px;
        }
        .feature-item p {
            color: #888;
            font-size: 0.9em;
        }
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
            <div class="feature-item">
                <i class="fas fa-link"></i>
                <h3>Short Links</h3>
                <p>Create custom short URLs</p>
            </div>
            <div class="feature-item">
                <i class="fas fa-chart-line"></i>
                <h3>Analytics</h3>
                <p>Track your link performance</p>
            </div>
            <div class="feature-item">
                <i class="fas fa-shield-alt"></i>
                <h3>Secure</h3>
                <p>Protected and reliable</p>
            </div>
        </div>
        
        <div style="margin-top: 40px;">
            <p style="font-size: 1em; color: #888;">To access your account, please use your personalized URL:</p>
            <code style="background: #f5f5f5; padding: 10px 20px; border-radius: 5px; display: inline-block; margin-top: 10px; color: #667eea; font-size: 1.1em;">
                https://gasss-team.me/?sub_id=your_username
            </code>
        </div>
    </div>
</body>
</html>
<?php
exit();
}

   
   if($_GET['sub_id']){
   $sub_id=mysqli_real_escape_string($link, $_GET['sub_id']);
   $sub_id=$sub_id;
   $query = mysqli_query($link, "SELECT * FROM generate WHERE sub_id = '$sub_id' ");
   $count=mysqli_num_rows($query);
   $row=mysqli_fetch_array($query);
   
   $sub_id=htmlspecialchars_decode($row['sub_id']);
   $password=htmlspecialchars_decode($row['password']);

$LOGIN_INFORMATION = array(
  $sub_id => $password
);

if(isset($_GET['help'])) {
  die('Include following code into every page you would like to protect, at the very beginning (first line):<br>&lt;?php include("' . str_replace('\\','\\\\',__FILE__) . '"); ?&gt;');
}?>
<!DOCTYPE html>
<html>
   <head>
      
      <title>
         <?php echo $sub_id ?>
      </title>
    <link href="favicon.ico" rel="icon" type="image/x-icon" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="dist/bootstrap.min.css" type="text/css" media="all">
    <link href="dist/jquery.bootgrid.css" rel="stylesheet" />
    <link href="//fonts.googleapis.com/css?family=Open+Sans:400,600" rel="stylesheet" type="text/css">
    <link href="//use.fontawesome.com/releases/v5.7.0/css/all.css" rel="stylesheet" type="text/css">
    <link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="dist/font-awesome-animation.min.css">
    <script src="dist/sweetalert.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@8"></script>
    <style>
    iframe{margin:auto;display:block;}input[type="text"]{font-family:consolas}.max{min-width:145px;text-align:left}.maxi{min-width:40px;text-align:left}img{border:1px solid #ddd;border-radius:4px}body{padding-top:10px}#radioBtn a.btn.save::before{font-family:fontAwesome;content:"\f00c\00a0"}.notActive{outline:0!important;box-shadow:none}.error{background-color:rgba(255,204,204,.2)!important}.success{background-color:rgba(204,232,255,.2)!important}button{outline:0!important}button:focus{box-shadow:none;outline:0}button:active{outline:0}.nav-tabs>li.active>a{background-color:none!important;border-bottom:none!important;border-top:none!important;border-radius:initial;border-left-style:none!important;border-right-style:none!important}.nav-tabs>li{border-radius:initial;border-left-style:initial;background-color:none!important}.nav-tabs{border-bottom:2px solid #ccc!important;border-top:2px solid #ccc!important;border-radius:initial;background-color:none!important}.rg-container{font-family:Helvetica,Arial,sans-serif;font-size:16px;line-height:1;margin:0;padding:1em 0;color:#1a1a1a}.rg-header{margin-bottom:1em}.rg-hed{font-family:"Benton Sans Bold",Helvetica,Arial,sans-serif;font-weight:700;font-size:1.35em;margin-bottom:.25em}.rg-subhed{font-size:1em;line-height:1.4em}.rg-source-and-credit{font-family:Georgia,"Times New Roman",Times,serif;width:100%;overflow:hidden;margin-top:1em}.rg-source{color:#7f7f7f;margin:0;float:left;font-weight:700;font-size:.75em;line-height:1.5em}.rg-source-0{color:#7f7f7f;margin:0;float:left;clear:both;font-weight:700;font-size:.75em;line-height:.5em}.rg-source .pre-colon{text-transform:uppercase}table.rg-table{margin:0 0 1em 0;width:100%;font-family:Helvetica,Arial,sans-serif;font-size:1em;border-collapse:collapse;border-spacing:0}table.rg-table *{-moz-box-sizing:border-box;box-sizing:border-box;margin:0;padding:0;border:0;font-size:100%;font:inherit;vertical-align:baseline;text-align:left;color:#333}table.rg-table thead{border-bottom:1px solid rgba(195,195,197,.3)}table.rg-table th{font-weight:700;padding:.5em;font-size:.85em;line-height:1.4}table.rg-table td{padding:.5em;font-size:.9em;line-height:1.4}table.rg-table .highlight td{font-weight:700}table.rg-table tr{border-bottom:1px solid rgba(195,195,197,.3);color:#222}table.rg-table .number{text-align:right}table.rg-table.zebra tr:nth-child(even){background:rgba(195,195,197,.1)}table.rg-table tr.highlight{background:#edece4}@media screen and (max-width:500px){.rg-container{max-width:500px;margin:0 auto}table.rg-table{display:block;width:100%}table.rg-table td.hide-mobile,table.rg-table th.hide-mobile,table.rg-table tr.hide-mobile{display:none}table.rg-table thead{display:none}table.rg-table tbody{display:block;width:100%}table.rg-table td:last-child{padding-right:0;border-bottom:2px solid #ccc}table.rg-table td,table.rg-table th,table.rg-table tr{display:block;padding:0}table.rg-table td[data-title]:before{content:attr(data-title) ":A0";font-weight:700;display:inline-block;content:attr(data-title);float:left;margin-right:.5em;font-size:.95em}table.rg-table tr{border-bottom:0;margin:0 0 1em 0;padding:.5em 0}table.rg-table tr:nth-child(even){background:0}table.rg-table td{padding:.5em 0 .25em 0;border-bottom:1px dotted #ccc;text-align:right}table.rg-table td:empty{display:none}table.rg-table .highlight td{background:0}table.rg-table tr.highlight{background:0}table.rg-table.zebra tr:nth-child(even){background:0}table.rg-table.zebra td:nth-child(even){background:rgba(195,195,197,.1)}}button{outline:0!important}button:focus{box-shadow:none;outline:0}button:active{outline:0}.line{margin:3px;padding:3px 3px;border:0;border-bottom:0 dashed #ccc}.table-fixed thead{position:sticky;position:-webkit-sticky;top:0;z-index:999;background-color:#f5f5f5;color:#424242}.table-fixed thead th{position:sticky;position:-webkit-sticky;top:0;z-index:999;background-color:#f5f5f5;color:#424242}.or{display:flex;justify-content:center;align-items:center;color:grey}.or:after,.or:before{content:"";display:block;background:grey;width:100%;height:1px} 
    </style>
   </head>
   <body style="font-family:Consolas;">
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
            <div class="container" style="width:600px;">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <button type="button" class="btn btn-xs btn-primary" data-row-id="0">
                        <span class="glyphicon glyphicon-user"></span><strong> <?php echo $_GET['sub_id']; ?></strong></button>
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
                allowOutsideClick: false,
                timer: 750
            });</script>"; }?>  
                        <form method="post">
                            <input class="form-control input-sm" name="access_login" placeholder="Username" type="hidden" value="<?php echo $_GET['sub_id']; ?>">
                            <div class="input-group">
                                <input type="password" class="form-control input-sm" id="access_password" name="access_password" autofocus="" placeholder="{password}">
                                <div class="input-group-btn">
                                    <button class="btn btn-default btn-sm" type="submit" type="button"><span class="fa fa-spinner fa-pulse fa-fw"></span><strong> User Login</strong></button>
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
}
else{return null;}if(isset($_GET["sub_id"]) && $count){?>
  <body style="font-family:Consolas;">
    <div class="container" style="width:650px;">
      <div class="panel panel-default">
        <div class="panel-heading">
          <code></code>
          <div class="pull-right">
            <button type="button" class="btn btn-xs btn-primary" id="command-add" data-row-id="0">
            <i class="fa fa-bell faa-ring animated"></i><strong> <?php echo $sub_id ?></strong></button>
          </div>
        </div>
        <div class="panel-body">
            <!--<table class="table table-condensed table-hover table-striped" cellspacing="0" style="table-layout: auto;">
            <tbody><tr class="info"><td class="block" style="width: 1%;white-space:nowrap;"><marquee scrollamount="3"><footer class="text-muted" style="text-decoration:none;"><i class="fa fa-superpowers" aria-hidden="true"> ngix:n-gix</i></footer></marquee></td></tr></tbody>
            </table>
            <hr style="margin:-7px;padding:-7px -7px;border:0;border-bottom:0px dashed #ccc">-->
          <div class="panel-footer" style="height:auto;">
            <img src="imge.png" height="50" width=100%>
          </div>
          <ul class="nav nav-tabs">
            <li id="tabgen" class="active"><a href="#gen" data-toggle="tab"><strong>GENERATE</strong></a></li>
            <!--<li id="tabpost"><a href="#post" data-toggle="tab"><strong>SHAREIMG</strong></a></li>-->
            <li id="tabsmturl"><a href="#smturl" data-toggle="tab"><strong>BULKURL</strong></a></li>
            <!--<li id="tabbt"><a href="#bt" data-toggle="tab"><strong>BITLY</strong></a></li>-->
            <li id="tabaddon"><a href="#addon" data-toggle="tab"><strong>ADDDOMAIN</strong></a></li>
            <li id="tabinfo"><a href="#info" data-toggle="tab"><strong>INFO</strong></a></li>
          </ul>
          <div id="myTabContent" class="tab-content">
              <div class="panel-footer" style="height:auto;" id="sm">
                <div class="input-group">
                  <div id="radioBtn" class="btn-group btn-group-justified btn-block">
                      <?php
                      $sql = "SELECT network FROM offering";
                      $result = mysqli_query($link, $sql);
                      if (mysqli_num_rows($result) > 0) {
                          while($row = mysqli_fetch_assoc($result)) {
                              $n = $row["network"];
                               echo '<a type="button" class="btn btn-default btn-sm notActive" data-toggle="user_lp" name="radioButton" id="radioButton" data-title="'.$n.'"><strong>'.$n.'</strong></a>';
                              }
                              } else {
                                  echo "No Network!";
                              }
                              ?>
                  </div>
                </div>
                </div>
                <hr style="margin:1px;padding:1px 1px;border:0;box-sizing:border-box;border-bottom:0">
            <div class="tab-pane fade active in" id="gen">
              <div class="panel-footer" style="height:auto;">
                  <div class="pull-left">
                    <select id="locrandom" class="input-sm" type="button">
                      <optgroup label="GLOBAL DOMAIN">
                        <option value="0" disabled="" class="bold-option">---[GLOBAL DOMAIN]---</option>
                        <option value="global" selected="selected" class="bold-option">[RANDOM GLOBAL DOMAIN]</option>
                        <?php $result = mysqli_query($link, "SELECT * FROM addondomain WHERE sub_domain='GLOBAL'");
                          while($row = mysqli_fetch_array($result)) {
                              echo '<option value="'.$row['domain'].'">'.$row['domain'].'</option>';
                              }?>
                      </optgroup>
                    </select>
                  </div>
                  <div class="text-right">
                    <select id="locdom" class="input-sm" type="button">
                      <optgroup label="USER DOMAIN">
                        <option value="0" disabled="" selected="selected" class="bold-option">---user domain---</option>
                        <option value="u_rand" class="bold-option">[RANDOM USER DOMAIN]</option>
                        <?php $result_user = mysqli_query($link, "SELECT * FROM addondomain WHERE sub_domain='$sub_id'");
                          while($row_user = mysqli_fetch_array($result_user)) {
                              echo '<option value="'.$row_user['domain'].'">'.$row_user['domain'].'</option>';
                              }?>
                      </optgroup>
                    </select>
                  </div>
                  </div>
                  <hr style="margin:1px;padding:1px 1px;border:0;border-bottom:0px dashed #ccc">
                  <div class="panel-footer" style="height:auto;">
                  <div class="input-group" style="margin:1px;padding:1px 1px;border:0;border-bottom:0px dashed #ccc">
                    <span class="input-group-addon maxi"><i class="fa fa-commenting" aria-hidden="true"></i></span>
                    <input class="form-control input-sm" id="fbtext" name="fbtext" onfocus="this.select();" autocomplete="off" placeholder="{og:title}" type="text">
                  </div>
                  <div class="input-group" style="margin:1px;padding:1px 1px;border:0;border-bottom:0px dashed #ccc">
                    <span class="input-group-addon maxi"><i class="fa fa-picture-o" aria-hidden="true"></i></span>
                    <input class="form-control input-sm" id="fbimg" name="fbimg" onfocus="this.select();" autocomplete="off" placeholder="{og:image}" type="text">
                  </div>
                  <div class="input-group" style="margin:1px;padding:1px 1px;border:0;border-bottom:0px dashed #ccc">
                    <span class="input-group-addon maxi"><i class="fa fa-link" aria-hidden="true"></i></span>
                    <input class="form-control input-sm" id="canonical_url" name="canonical_url" onfocus="this.select();" autocomplete="off" placeholder="{og:canonical}" type="text">
                  </div>
                  </div>
                  <hr style="margin:1px;padding:1px 1px;border:0;border-bottom:0px dashed #ccc">
                  <div class="panel-footer" style="height:auto;">
                  <div class="input-group">
                    <input style="width:50%;" class="form-control input-sm" id="apibranch" name="apibranch" onfocus="this.select();" autocomplete="off" placeholder="{branch_key}" type="text">
                    <select style="width:25%;" id="sel_url" class="form-control input-sm">
                      <optgroup label="SELECT OPTION">
                        <option selected="selected" value="0">branch off</option>
                        <option value="1">branch on</option>
                      </optgroup>
                    </select>
                    <select style="width:25%;" id="sel_pick" class="form-control input-sm">
                      <optgroup label="SELECT SHORTURL">
                        <option selected="selected" value="0">app.link</option>
                        <option value="1">bnc.lt</option>
                      </optgroup>
                    </select>
                    <span class="input-group-btn">
                    <button class="btn btn-default btn-sm" data-loading-text="Generate URL ..." id="branch" type="button"><strong><span class="fa fa-external-link" aria-hidden="true"></span> GENERATE URL</strong></button>
                    </span>
                  </div>
                  </div>
                  <hr style="margin:1px;padding:1px 1px;border:0;border-bottom:0px dashed #ccc">
              <div class="panel-footer" style="height:auto;">
                <textarea class="form-control input-sm" id="status" rows="3" onfocus="this.select();" style="resize:none;" required="required"></textarea>
                  <hr style="margin:1px;padding:1px 1px;border:0;border-bottom:0px dashed #ccc">
                  <select id="sts" class="form-control input-sm">
                      <optgroup label="STATUS">
                          <option class="btn btn-sm btn-default" value="0">{disable}</option>
                        <option class="btn btn-sm btn-default" value="a1">{random status}</option>
                        <option class="btn btn-sm btn-default" value="a2">{input status}</option>
                      </optgroup>
                    </select>
              </div>
              <hr style="margin:1px;padding:1px 1px;border:0;border-bottom:0px dashed #ccc">
              <div class="panel-footer" style="height:auto;">
                <!---<div class="input-group">
                  <span class="input-group-addon max"><strong class="rg-source-0">* 
                  <a href="//app.bitly.com/bitlinks/?actions=profile&actions=accessToken" rel="noopener noreferrer" target="popup" onclick="window.open('//app.bitly.com/bitlinks/?actions=profile&actions=accessToken','popup','width=auto,height=100%,scrollbars=no,resizable=no'); return false;"> BITLY ACCESS TOKEN</a></strong></span>
                  <input autocomplete="off"  class="form-control input-sm" onfocus="this.select();" id="bitkey" name="bitkey" placeholder="{optional} 077f740318ea737..." type="text">
                </div>
                <hr style="margin:1px;padding:1px 1px;border:0;box-sizing:border-box;border-bottom:0">--->
                  <div class="input-group">
                    <span class="input-group-addon maxi"><i class="fa fa-link" aria-hidden="true"></i></span>
                    <input autocomplete="off" style="width:45%;"  class="form-control input-sm" onfocus="this.select();" id="b_short" name="b_short" placeholder="{shorturl}" onselectstart="return false" onpaste="return false;" oncut="return false" ondrag="return false" ondrop="return false" type="text">
                    <select style="width:30%;" id="net_pick" class="form-control input-sm">
                      <optgroup label="SELECT DEBUUGER">
                        <option value="a" selected="selected">shortener</option>
                        <option value="b">l.fb.com</option>
                        <option value="c">web.fb.com</option>
                        <option value="d">p.fb.com</option>
                        <option value="e">l.ig.com</option>
                        <option value="f">l.wl.co</option>
                      </optgroup>
                    </select>
                    <select style="width:25%;" id="uri" class="form-control input-sm">
                      <optgroup label="SELECT SHORTURL">
                        <option class="btn btn-sm btn-default" value="ixg">ixg.llc</option>
                        <option class="btn btn-sm btn-default" value="tco">is.gd</option>
                        <option class="btn btn-sm btn-default" value="dnt">v.gd</option>
                        <option class="btn btn-sm btn-default" value="jmp">x.gd</option>
                        <option class="btn btn-sm btn-default" value="rut">v.ht</option>
                        <option class="btn btn-sm btn-default" value="cutt">cutt.us</option>
                        <option class="btn btn-sm btn-default" value="bdeb">longurl</option>
                        <option class="btn btn-sm btn-default" value="bomso">ssur.cc</option>
                      </optgroup>
                    </select>
                    <span class="input-group-btn">
                    <button class="btn btn-default btn-sm" data-loading-text="shorting URL ..." id="exec" type="button"><strong><span class="fa fa-share-square-o" aria-hidden="true"></span> SHORT URL</strong></button>
                    <button aria-label="Copy" class="btn btn-default btn-sm" data-copytarget="#b_short" type="button"><i class="fa fa-clone" aria-hidden="true" data-copytarget="#b_short"></i></button></span>
                    </span>
                  </div>
                  <div class="form-group" id="hidetxt" style="margin:1px;padding:1px 1px;border:0;border-bottom:0px dashed #ccc" hidden>
                  <textarea class="form-control input-sm" id="trtxt" rows="3" onfocus="this.select();" style="resize:none;"></textarea>
                  <hr style="margin:1px;padding:1px 1px;border:0;border-bottom:0px dashed #ccc">
                  <button aria-label="Copy" class="form-control btn btn-default btn-sm" onclick="copy()" type="button"><strong><i class="fa fa-clone" aria-hidden="true" data-copytarget="#trtxt"></i> Copy Status</strong></button>
                </div>
                <hr style="margin:1px;padding:1px 1px;border:0;border-bottom:0px dashed #ccc">
                                <div class="input-group">
                  <span class="input-group-addon max"><strong class="rg-source-0">* 
                  IG BIO SHIMLINK</strong></span>
                </div>
                <textarea class="form-control input-sm" id="shimlink" name="shimlink" placeholder="ATOCVvkwE4s92..." rows="2" onfocus="this.select();" style="resize:none;"></textarea>
                <hr style="margin:1px;padding:1px 1px;border:0;box-sizing:border-box;border-bottom:0">

                  <div class="input-group" id="hidelink" hidden>
                    <span class="input-group-addon maxi"><span class="fa fa-link" aria-hidden="true"></span></span>
                    <input autocomplete="off" type="text" id="l_short" name="l_short" class="form-control input-sm" onfocus="this.select();" placeholder="{fb_debugger}" onselectstart="return false" onpaste="return false;" oncut="return false" ondrag="return false" ondrop="return false">
                    <span class="input-group-btn">
                    <button aria-label="Copy" class="btn btn-default btn-sm" data-copytarget="#l_short" type="button"><i class="fa fa-clone" aria-hidden="true" data-copytarget="#l_short"></i></button>
                    </span>
                  </div>
              </div>
            </div>
            <!--postimage-->
<!--            <div class="tab-pane fade" style="text-align: center;" id="bt">
                <iframe src="https://bitly-longurl-short.blogspot.com/" style="overflow: hidden; border: 0; margin: auto; padding: 0;" scrolling="no" frameborder="0" marginheight="0px" marginwidth="0px" height="450px" width="587px" allowfullscreen></iframe>
                </div>-->
            <div class="tab-pane fade" id="post">
              <div class="panel-footer" style="height:auto;width:100%;">
                  <div class="input-group">
                    <span class="input-group-addon max"><strong class="rg-source-0">* 
                    <a href="//dashboard.branch.io/account-settings/profile" rel="noopener noreferrer" target="popup" onclick="window.open('//dashboard.branch.io/account-settings/profile','popup','width=auto,height=100%,scrollbars=no,resizable=no'); return false;"> BRANCH.IO KEY LIVE</a></strong></span>
                    <input autocomplete="off" type="text" id="key" class="form-control input-sm" onfocus="this.select();" placeholder="key_live_pnTU3I..." onselectstart="return false" type="text">
                    <input autocomplete="off" class="form-control input-sm" id="sel_net_img_p" name="sel_net_img_p" onfocus="this.select();" type="hidden">
                  </div>
                  <hr style="margin:1px;padding:1px 1px;border:0;border-bottom:0px dashed #ccc">
                  <div class="input-group">
                    <span class="input-group-addon maxi"><i class="fa fa-link" aria-hidden="true"></i></span>
                    <input type="text" id="og_url" name="og_url" class="form-control input-sm" onfocus="this.select();" placeholder="og_url {custom user domain}" autocomplete="off" type="text" style="width:65%;">
                    <select class="form-control input-sm" id="sel_dom_img" style="width:35%;">
                      <optgroup label="SELECT DOMAIN">
                        <option class="btn btn-sm btn-defaublt" selected="selected" value="{global domain}">
                          {global domain}
                        </option>
                        <option class="btn btn-sm btn-default" value="{user domain}">
                         {user domain}
                        </option>
                        <option class="btn btn-sm btn-default" value="{custom domain}">
                         {custom domain}
                        </option>
                      </optgroup>
                    </select>
                  </div>
                  </div>
                  <hr style="margin:1px;padding:1px 1px;border:0;border-bottom:0px dashed #ccc">
                  <div class="panel-footer" style="height:auto;width:100%;">
                  <div class="input-group"> 
                    <span class="input-group-addon maxi"><i class="fa fa-picture-o" aria-hidden="true"></i></span>
                    <input type="text" id="og_image_url" class="form-control input-sm" onfocus="this.select();" placeholder="og_image_url {kosongi jika pakai gambar random}" autocomplete="off" type="text">
                  </div>
              </div>
              <hr style="margin:1px;padding:1px 1px;border:0;border-bottom:0px dashed #ccc">
              <div class="panel-footer" style="height:auto;width:100%;">
                  <div class="input-group">
                    <span class="input-group-addon maxi"><i class="fa fa-bars" aria-hidden="true"></i></span>
                    <select id="app_pick" class="form-control input-sm">
                      <optgroup label="SELECT APPID">
                        <option value="200368456664008" selected="selected">We Heart It</option>
                        <option value="87741124305">YouTube</option>
                        <option value="166222643790489">Metacafe</option>
                        <option value="462754987849668">Flickr.com</option>
                        <option value="187960271237149">Detik.com</option>
                        <option value="160621007401976">Liputan6.com</option>
                        <option value="324557847592228">Kompas.com</option>
                        <option value="332404380172618">Tempo.co</option>
                      </optgroup>
                    </select>
                    <input type="hidden" id="apppick" name="apppick" class="form-control input-sm" onselectstart="return false" onpaste="return false;" onCut="return false" onDrag="return false" onDrop="return false">
                    <span class="input-group-btn">
                    <button class="btn btn-default btn-sm" id="btnapp" data-loading-text="debugging URL ..." type="button"><strong><span class="fa fa-picture-o" aria-hidden="true"></span> GET Share Image</strong></button>
                  </div>
                <hr style="margin:1px;padding:1px 1px;border:0;border-bottom:0px dashed #ccc">
                <div class="input-group">
                    <span class="input-group-addon maxi"><span class="fa fa-link" aria-hidden="true"></span></span>
                  <input autocomplete="off" type="text" id="urltemp" class="form-control input-sm" onfocus="this.select();" placeholder="https://web.facebook.com/dialog/feed..." onselectstart="return false" onpaste="return false;" oncut="return false" onkeypress="return false;" ondrag="return false" ondrop="return false" type="text">
                  <span class="input-group-btn">
                  <button aria-label="Copy" class="btn btn-default btn-sm" data-copytarget="#urltemp" type="button"><i class="fa fa-clone" aria-hidden="true" data-copytarget="#urltemp"></i></button>
                  </span>
                </div>
                <hr style="margin:1px;padding:1px 1px;border:0;border-bottom:0px dashed #ccc">
                <div class="input-group">
                    <span class="input-group-addon maxi"><span class="fa fa-link" aria-hidden="true"></span></span>
                  <input autocomplete="off" type="text" id="linkurl" class="form-control input-sm" onfocus="this.select();" placeholder="https://..." onselectstart="return false" onpaste="return false;" oncut="return false" onkeypress="return false;" ondrag="return false" ondrop="return false" type="text">
                  <span class="input-group-btn">
                  <button aria-label="Copy" class="btn btn-default btn-sm" data-copytarget="#linkurl" type="button"><i class="fa fa-clone" aria-hidden="true" data-copytarget="#linkurl"></i></button>
                  </span>
                </div>
                <hr style="margin:1px;padding:1px 1px;border:0;border-bottom:0px dashed #ccc">
                <div class="input-group">
                    <span class="input-group-addon maxi"><span class="fa fa-link" aria-hidden="true"></span></span>
                  <input autocomplete="off" type="text" id="isgdbmr" class="form-control input-sm" onfocus="this.select();" placeholder="https://..." onselectstart="return false" onpaste="return false;" oncut="return false" onkeypress="return false;" ondrag="return false" ondrop="return false" type="text">
                  <span class="input-group-btn">
                  <button aria-label="Copy" class="btn btn-default btn-sm" data-copytarget="#isgdbmr" type="button"><i class="fa fa-clone" aria-hidden="true" data-copytarget="#isgdbmr"></i></button>
                  </span>
                </div>
              </div>
            </div>
            <div class="tab-pane fade" id="smturl">
              <div class="panel-footer" style="height:auto;">
                <form action="/api/aaa.php" id="branch_form" method="post" name="branch_form">
                  <div class="input-group">
                    <select class="form-control input-sm" id="sel_bnc" style="width:20%;" disabled>
                      <optgroup label="SELECT BRANCH">
                        <option class="btn btn-sm btn-default"value="0">
                          bnc.lt
                        </option>
                        <option class="btn btn-sm btn-default" value="1">
                         app.link
                        </option>
                        <option class="btn btn-sm btn-default" selected="selected" value="2">
                         OFF
                        </option>
                      </optgroup>
                    </select>
                    <select class="form-control input-sm" id="branch_uri" style="width:25%;">
                      <optgroup label="SELECT SHORTURL">
                        <option class="btn btn-sm btn-default" selected="selected" value="ixg_i">
                          ixg.llc
                        </option>
                        <option class="btn btn-sm btn-default" value="4_branch">
                          longurl
                        </option>
                        <option class="btn btn-sm btn-default" value="iisgd">
                          is.gd
                        </option>
                        <option class="btn btn-sm btn-default" value="ivgd">
                          v.gd
                        </option>
                        <option class="btn btn-sm btn-default" value="ixgd">
                          x.gd
                        </option>
                        <option class="btn btn-sm btn-default" value="ivht">
                          v.ht
                        </option>
                        <option class="btn btn-sm btn-default" value="icutt">
                          cutt.us
                        </option>
                        <option class="btn btn-sm btn-default" value="sur">
                          ssur.cc
                        </option>
                      </optgroup>
                    </select>
                    <select class="form-control input-sm" id="fb_uri" style="width:25%;">
                      <optgroup label="SELECT SHORTURL">
                        <option class="btn btn-sm btn-default" selected="selected" value="0">
                          {shorturl}
                        </option>
                        <option class="btn btn-sm btn-default" value="1">
                         {l.fb}
                        </option>
                        <option class="btn btn-sm btn-default" value="p_fb">
                         {p.fb}
                        </option>
                        <option class="btn btn-sm btn-default" value="w_fb">
                         {web.fb}
                        </option>
                        <option class="btn btn-sm btn-default" value="ig_s">
                         {l.ig}
                        </option>
                        <option class="btn btn-sm btn-default" value="lwl">
                         {l.wl.co}
                        </option>
                      </optgroup>
                    </select>
                    <select class="form-control input-sm" id="sel_dom" style="width:30%;">
                      <optgroup label="SELECT DOMAIN">
                        <option class="btn btn-sm btn-default" selected="selected" value="0">
                          {global domain}
                        </option>
                        <option class="btn btn-sm btn-default" value="1">
                         {user domain}
                        </option>
                      </optgroup>
                    </select>
                    </div>
<!--                  <hr style="margin:1px;padding:1px 1px;border:0;box-sizing:border-box;border-bottom:0">
                    <div class="input-group">
                    <span class="input-group-addon max"><strong class="rg-source-0">* <a href="//dashboard.branch.io/account-settings/profile" onclick="window.open('//dashboard.branch.io/account-settings/profile','popup','width=auto,height=100%,scrollbars=no,resizable=no'); return false;" rel="noopener noreferrer" target="popup">BRANCH.IO KEY LIVE</a></strong></span>
                    <input autocomplete="off" class="form-control input-sm" id="branch_key" name="branch_key" onfocus="this.select();" placeholder="* key_live_dmT1jTtqVDn..." required="" type="text" disabled>
                    </div>
                  <hr style="margin:1px;padding:1px 1px;border:0;box-sizing:border-box;border-bottom:0">
                  <div class="input-group">
                    <span class="input-group-addon max">
                    <strong class="rg-source-0">* <a href="//app.bitly.com/bitlinks/?actions=profile&amp;actions=accessToken" onclick="window.open('//app.bitly.com/bitlinks/?actions=profile&amp;actions=accessToken','popup','width=auto,height=100%,scrollbars=no,resizable=no'); return false;" rel="noopener noreferrer" target="popup">BITLY ACCESS TOKEN</a></strong></span>
                    <input autocomplete="off" class="form-control input-sm" id="bit_key" name="bit_key" onfocus="this.select();" placeholder="* 077f740318ea737..." required="" style="width:50%;" type="text">
                  </div>
              <hr style="margin:1px;padding:1px 1px;border:0;border-bottom:0px dashed #ccc">
                <div class="input-group">
                  <span class="input-group-addon max"><strong class="rg-source-0">* 
                  IG BIO SHIMLINK</strong></span>
                </div>
                <textarea class="form-control input-sm" id="igshiml" name="igshiml" ondrag="return false" ondrop="return false" onfocus="this.select();" placeholder="ATOA_8A82u1CeQHuuzn..." rows="3" style="resize:none;" required></textarea>-->
                  </div>
                    <div class="input-group">
                      <input autocomplete="off" class="form-control input-sm" id="longurl_i" name="longurl_i" onfocus="this.select();" placeholder="{longurl}"type="hidden" value="1">
                      <input autocomplete="off" class="form-control input-sm" id="b_subid" name="b_subid" onfocus="this.select();" placeholder="{longurl}"type="hidden" value="<?php echo $sub_id; ?>">
                  </div>
                  <input autocomplete="off" class="form-control input-sm" id="branch_pick" name="branch_pick" onfocus="this.select();" type="hidden">
                  <input autocomplete="off" class="form-control input-sm" id="fb_pick" name="fb_pick" onfocus="this.select();" type="hidden">
                  <input autocomplete="off" class="form-control input-sm" id="sel_dom_p" name="sel_dom_p" onfocus="this.select();" type="hidden">
                  <input autocomplete="off" class="form-control input-sm" id="sel_bnctext" name="sel_bnctext" onfocus="this.select();" type="hidden">
                  <hr style="margin:1px;padding:1px 1px;border:0;box-sizing:border-box;border-bottom:0">
                  <div class="panel-footer" style="height:auto;">
                  <div class="input-group" style="margin:1px;padding:1px 1px;border:0;border-bottom:0px dashed #ccc">
                    <span class="input-group-addon maxi"><i class="fa fa-commenting" aria-hidden="true"></i></span>
                    <input class="form-control input-sm" id="b_fbtext" name="b_fbtext" onfocus="this.select();" autocomplete="off" placeholder="{og:title}" type="text">
                  </div>
                  <div class="input-group" style="margin:1px;padding:1px 1px;border:0;border-bottom:0px dashed #ccc">
                    <span class="input-group-addon maxi"><i class="fa fa-picture-o" aria-hidden="true"></i></span>
                    <input class="form-control input-sm" id="b_fbimg" name="b_fbimg" onfocus="this.select();" autocomplete="off" placeholder="{og:image}" type="text">
                  </div>
                  <div class="input-group" style="margin:1px;padding:1px 1px;border:0;border-bottom:0px dashed #ccc">
                    <span class="input-group-addon maxi"><i class="fa fa-link" aria-hidden="true"></i></span>
                    <input class="form-control input-sm" id="b_canonical_url" name="b_canonical_url" onfocus="this.select();" autocomplete="off" placeholder="{og:canonical}" type="text">
                  </div>
                  <div class="input-group" style="margin:1px;padding:1px 1px;border:0;border-bottom:0px dashed #ccc">
                      <select class="form-control input-sm" id="singleSelectValueDDjQuery">
                      <optgroup label="SELECT LANDING PAGE">
                        <option class="btn btn-sm btn-default" selected="selected" value="0">
                          {No Landing}
                        </option>
                        <option class="btn btn-sm btn-default" value="1">
                         {Landing Page}
                        </option>
                      </optgroup>
                    </select>
                    <span class="input-group-addon maxi"><i class="fa fa-repeat" aria-hidden="true"> LIMIT</i></span>
                    <input autocomplete="off" class="form-control input-sm" id="limit_i" name="limit_i" value="10" required="" onfocus="this.select();" placeholder="{limit}" type="number" min="1" max="25"> 
                    <input autocomplete="off" class="form-control input-sm" id="netpick" name="netpick" onfocus="this.select();" type="hidden">
                    <input autocomplete="off" class="form-control input-sm" id="textFieldValueJQ" name="textFieldValueJQ" value="0" type="hidden">
                    <span class="input-group-btn">
                    <button class="btn btn-default btn-sm" id="branch_btn" data-loading-text="shorting URL ..." type="submit"><strong><span class="fa fa-share-square-o" aria-hidden="true"></span> GET BULK URL</strong></button>
                    </span>
                  </div>
                  </div>
                </form>
                <hr style="margin:1px;padding:1px 1px;border:0;box-sizing:border-box;border-bottom:0">
                <div class="panel-footer" style="height:auto;">
                <div hidden="" id="branch_genlink" style="display:none;"></div>
                  <textarea class="form-control input-sm" id="branch_result" ondrag="return false" ondrop="return false" onfocus="this.select();" placeholder="http://..." rows="5" style="resize:none;"></textarea>
                  <hr style="margin:1px;padding:1px 1px;border:0;box-sizing:border-box;border-bottom:0">
                  <button aria-label="Copy" class="form-control btn btn-default btn-sm" onclick="copytext()" type="button"><strong><i class="fa fa-clone" data-copytarget="#branch_result" aria-hidden="true"></i> COPY SHORTURL</strong></button>

            </div></div>
            <!--end-->
            <div class="tab-pane fade" id="addon">
              <blockquote>
                <p class="text-warning">Nameserver:</p>
                                <footer class="text-muted">ns1-ns4.mysecurecloudhost.com</footer>
              </blockquote>
              <hr style="margin:1px;padding:1px 1px;border:0;border-bottom:0px dashed #ccc">
              <div class="panel-footer" style="height:auto;">
                <div class="input-group">
                  <span class="input-group-addon maxi"><i class="fa fa-plus-square" aria-hidden="true"></i></span>
                  <input readonly="readonly" type="hidden" class="form-control input-sm" id="userid" name="userid" value="<?php echo $sub_id ?>">
                  <input autocomplete="off" type="text" class="form-control input-sm" id="domain" name="domain" onfocus="this.select();" autofocus="" placeholder="{addon_domain} domain.ltd">
                  <span class="input-group-btn btn-block"><button class="btn btn-default btn-sm" id="addondom" type="button"  data-loading-text="Addon Domain ..."><strong>Addon Domian</strong></button>
                  </span>
                </div>
              </div>
              <hr style="margin:3px;padding:3px 3px;border:0;border-bottom:0px solid #ccc">
              <table id="table_domain" class="responsive nowrap unstackable table table-hover table-fixed" cellspacing="0" style="table-layout: auto;">
                <thead>
                  <tr>
                    <th id="domain" class="rg-source col-xs-8" style="text-align: left;">DOMIAN</th>
                    <th id="sub_domain" class="rg-source col-xs-2" style="text-align: left;">SUBID</th>
                    <th id="domain" class="rg-source col-xs-2" style="text-align: left;">ACTION</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
              <hr style="margin:10px;padding:3px 3px;border:0;border-bottom:2px solid #ccc">
              <blockquote>
                <p class="text-info">Temporary Email Management:</p>
                <footer class="text-muted">Manage temporary email addresses for testing and verification</footer>
              </blockquote>
              <hr style="margin:1px;padding:1px 1px;border:0;border-bottom:0px dashed #ccc">
              <div class="panel-footer" style="height:auto;">
                <div class="input-group">
                  <span class="input-group-addon maxi"><i class="fa fa-envelope" aria-hidden="true"></i></span>
                  <input autocomplete="off" type="text" class="form-control input-sm" id="temp_email" name="temp_email" onfocus="this.select();" placeholder="email@example.com">
                  <span class="input-group-btn btn-block"><button class="btn btn-default btn-sm" id="addtempemail" type="button"  data-loading-text="Adding Email ..."><strong>Add Temp Email</strong></button>
                  </span>
                </div>
              </div>
              <hr style="margin:3px;padding:3px 3px;border:0;border-bottom:0px solid #ccc">
              <table id="table_temp_email" class="responsive nowrap unstackable table table-hover table-fixed" cellspacing="0" style="table-layout: auto;">
                <thead>
                  <tr>
                    <th id="email" class="rg-source col-xs-8" style="text-align: left;">EMAIL</th>
                    <th id="created" class="rg-source col-xs-2" style="text-align: left;">CREATED</th>
                    <th id="action" class="rg-source col-xs-2" style="text-align: left;">ACTION</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>

            </div>
            <!--end-->
            <div class="tab-pane fade" id="info">
              <div class="panel-footer" style="height:auto;">
                <div class="input-group">
                  <span class="input-group-addon max"><strong class="rg-source-0">* Facebook accessToken</strong></span>
                  <input autocomplete="off" type="text" class="form-control input-sm" id="accessToken" name="accessToken" onfocus="this.select();" autofocus="" placeholder="EAAAAZAw4FxQIBABcNNAzZA4D...">
                  <span class="input-group-btn btn-block"><button class="btn btn-default btn-sm" id="cdomainbtn" type="button"  data-loading-text="Checking Domain ..."><strong>Check Domain Debugger</strong></button>
                  </span>
                </div>
              </div>
              <hr style="margin:3px;padding:3px 3px;border:0;border-bottom:0px solid #ccc">
              <table id="ctable_domain" class="responsive nowrap unstackable table table-hover table-fixed" cellspacing="0" style="table-layout: auto;">
                <thead>
                  <tr>
                    <th id="cdomain" class="rg-source col-xs-8" style="text-align: left;">DOMIAN</th>
                    <th id="cinfo" class="rg-source col-xs-4" style="text-align: left;">INFO</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
        </div>
        <!-- end -->
        <input type="hidden" class="form-control input-sm" id="user_lp" name="user_lp">
        <input type="hidden" id="pick" name="pick" class="form-control input-sm" onselectstart="return false" onpaste="return false;" onCut="return false" onDrag="return false" onDrop="return false">
        <input type="hidden" id="deb" name="deb" class="form-control input-sm" onselectstart="return false" onpaste="return false;" onCut="return false" onDrag="return false" onDrop="return false">
        <input type="hidden" id="genurl" name="genurl" class="form-control input-sm" onselectstart="return false" onpaste="return false;" onCut="return false" onDrag="return false" onDrop="return false">
        <input type="hidden" id="pickurl" name="pickurl" class="form-control input-sm" onselectstart="return false" onpaste="return false;" onCut="return false" onDrag="return false" onDrop="return false">
        <input type="hidden" id="sub_id" name="sub_id" class="form-control input-sm" onselectstart="return false" onpaste="return false;" onCut="return false" onDrag="return false" onDrop="return false" value="<?php echo strtolower($sub_id); ?>">
      </div>
      <hr style="margin:-10px;padding:-10px -10px;border:0;box-sizing:border-box;border-bottom:0">
      <div class="rg-source" style="float:right"><footer class="text-muted" style="text-decoration:none;"><i class="fa fa-superpowers" aria-hidden="true"> ngix:n-gix</i></footer></div>
    </div>
    <script src="dist/jquery.min.js" type="text/javascript"></script>
    <script src="api/jquery.form.js" type="text/javascript"></script>
    <script src="//apis.google.com/js/client.js"></script>
    <script src="dist/bootstrap.min.js"></script>
    <script src="dist/jquery.bootgrid.min.js"></script>
    <script type="text/javascript">
      $(document).ready(function () {
            document.querySelectorAll('[name=radioButton]')[0].classList.add("active", "save");
            var divId = document.getElementById("radioButton");
            document.getElementById("user_lp").value=divId.getAttribute("data-title");
            document.getElementById("sel_net_img_p").value=divId.getAttribute("data-title");
            document.getElementById("netpick").value=divId.getAttribute("data-title");
          $("img").on("contextmenu",function(){
              return false;
              });
      	$("#fb_pick").val(0);
      	$("#igshiml").prop('disabled', true);
      	$("#fb_uri").change(fb_uri);
      	function fb_uri() {
      	    if(this.value == 'ig_s'){
      		    $("#igshiml").prop('disabled', false);
      	    }
      		else{
      		    $("#igshiml").prop('disabled', true);
      		}
      		$("#fb_pick").val(this.value);
      	}
      	
      	$("#sts").change(stsx);
      	$("#status").attr('disabled','disabled');
      	function stsx() {
      	    if(this.value == 'a1'){
      		    $("#status").val('{random}').attr('disabled','disabled');
      	    }
      	    else if(this.value == 'a2'){
      		    $("#status").val('').removeAttr('disabled');
      	    }
      		else{
      		    $("#status").val('').attr('disabled','disabled');
      		}
      	}
    $("#singleSelectValueDDjQuery").on("change",function(){
        //Getting Value
        var selValue = $("#singleSelectValueDDjQuery").val();
        //Setting Value
        $("#textFieldValueJQ").val(selValue);
    });    
      	
      	$("#sel_dom_p").val(0);
      	$("#sel_dom").change(sel_dom);
      	function sel_dom() {
      		$("#sel_dom_p").val(this.value);
      	}
      	$("#sel_bnctext").val(2);
      	$("#sel_bnc").change(sel_bnc);
      	function sel_bnc() {
      		$("#sel_bnctext").val(this.value);
      	}
      	$("#og_url").val('{global domain}').attr('disabled','disabled');
      	$("#sel_dom_img").change(sel_dom_img);
      	function sel_dom_img() {
      		$("#og_url").val(this.value);
      		if(this.value == '{global domain}'){
      		    $("#og_url").attr('disabled','disabled');
      		}
      		else if(this.value == '{user domain}'){
      		    $("#og_url").attr('disabled','disabled');
      		}
      		else{
      		    $("#og_url").val('').removeAttr('disabled');
      		}

      	}

     $('#radioBtn a').on('click', function () {
          var sel = $(this).data('title'),
          tog = $(this).data('toggle');
          $('#' + tog).prop('value', sel);
          $('a[data-toggle="' + tog + '"]').not('[data-title="' + sel + '"]').removeClass('active save').addClass('notActive');
          $('a[data-toggle="' + tog + '"][data-title="' + sel + '"]').removeClass('notActive').addClass('active save');
          $('#sel_net_img_p').val(sel);
          $('#netpick').val(sel);
          });
      	function makeid(length) {
      		var result = '';
      		var characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
      		var charactersLength = characters.length;
      		for (var i = 0; i < length; i++) {
      			result += characters.charAt(Math.floor(Math.random() * charactersLength));
      		}
      		return result;
      	}
      
      	var options = {
      		target: '#branch_genlink', // target element(s) to be updated with server response 
      		beforeSubmit: showRequest, // pre-submit callback 
      		success: showResponse // post-submit callback 
      	};
      	$('#branch_form').ajaxForm(options);
      
      	function showRequest(formData, jqForm, options) {
      		var queryString = $.param(formData);
      		$("#branch_btn").button('loading');
      		$("#branch_result").val("waiting...");
      		return true;
      	}
      
      	function showResponse(responseText, statusText, xhr, $form) {
      		$("#branch_result").val(responseText.replace(/,/g, '\n'));
      		$("#branch_btn").button('reset');
      		$("#res").text('redirect_url: ' + $("#longurl_i").val());
      	}
      	$("#branch_result").keypress(function (e) {
      		e.preventDefault();
      		return false;
      	});
      
      	$('#tabgen, #tabaddon, #tabsmturl, #tabpost').on('click', function (e) {
      		e.preventDefault();
      		$('#b_short, #urltemp, #bnctemp, #trtxt1, #smurltemp, #og_image_url,#urltemp,#l_short').val('').removeClass("error success");
      		$('#hidetxt1, #hidelink1, #hidetxt').hide().removeClass("error success");
      	});
      	
      	$('#tabinfo, #tabaddon, #tabbt').on('click', function (e) {
      		e.preventDefault();
      		$('#sm').hide();
      		$('#linkurl').val("");
      		$('#isgdbmr').val("");
      		$('#btnapp').button('reset');
      	});
      	$('#tabgen, #tabpost, #tabsmturl').on('click', function (e) {
      		e.preventDefault();
      		$('#sm').show();
      		$('#linkurl').val("");
      		$('#isgdbmr').val("");
      		$('#btnapp').button('reset');
      	});
      	$("#apppick").val('200368456664008');
      	$("#app_pick").change(change);
      
      	$("#langAPI1").val("en");
      	$("#lang1").change(lang_pick1);
      
      	function lang_pick1() {
      		$("#langAPI1").val(this.value);
      	}
      
      	function change() {
      		$("#apppick").val(this.value);
      	}
      	$("#bncpick").val(0);
      	$("#bnc_pick").change(changebnc);
      
      	function changebnc() {
      		$("#bncpick").val(this.value);
      	}
      	$("#b_pick").val(1);
      	$("#b_uri").change(b_uri);
      
      	function b_uri() {
      		$("#b_pick").val(this.value);
      	}
      	$("#branch_uri").change(branch_uri);
      	$("#branch_pick").val('ixg_i');
      	$("#bit_key").attr('disabled','disabled');
      	function branch_uri() {
      		$("#branch_pick").val(this.value);
      	}
      	var btnbnc, btnapp, sub_id, dev_email, user_id, og_title, og_description, og_image_url, prm1, prm2, trtxt1;
      	$("#btnbnc").click(function () {
      		if (!$("#user_id").val() || !$("#dev_email").val()) {
      			swal.fire({
      				position: 'top-end',
      				type: 'error',
      				title: '* Required fields!',
      				showConfirmButton: false,
      				timer: 750
      			});
      			return false;
      		}
      		sub_id = $.trim($("#sub_id").val()),
      			dev_email = $.trim($("#dev_email").val()),
      			langAPI = $('#langAPI1').val(),
      			status = $.trim($("#status1").val()),
      			user_id = $.trim($("#user_id").val()),
      			prm2 = $('#bncpick').val(),
      			btnbnc = $(this);
      		btnbnc.button('loading');
      		$("#bnctemp").val('Waiting...');
      		$("#trtxt1").val('Waiting...');
      		start1(0);
      	});
      
      	$("#smbtn").click(function () {
      		if (!$("#user_id").val() || !$("#dev_email").val()) {
      			swal.fire({
      				position: 'top-end',
      				type: 'error',
      				title: '* Required fields!',
      				showConfirmButton: false,
      				timer: 750
      			});
      			return false;
      		}
      		sub_id = $.trim($("#sub_id").val()),
      			dev_email = $.trim($("#dev_email").val()),
      			user_id = $.trim($("#user_id").val()),
      			smbtn = $(this);
      		smbtn.button('loading');
      		$("#smurltemp").val('Waiting...');
      		start2(0);
      	});
      
      	function start2(start2) {
      		$.post('/api/sm.api.tco.php', {
      			dev_email: dev_email,
      			user_id: user_id,
      			sub_id: sub_id
      		}).done(function (data) {
      			jQuery.parseJSON(JSON.stringify(data));
      			var shortUrl = data[0]['shorturl'];
      			if (shortUrl == null || shortUrl == 'https://') {
      				$("#smurltemp").val("* Request branch.io or generate_url");
      			} else {
      				if (!status) {
      					$("#smurltemp").val($.trim(shortUrl));
      				} else {
      					$('#smurltemp').val(shortUrl);
      				}
      			}
      			smbtn.button('reset');
      		})
      	}
      
      	function start1(start1) {
      		$.post('/api/smurl.api.php', {
      			dev_email: dev_email,
      			user_id: user_id,
      			langAPI: langAPI,
      			status: status,
      			sub_id: sub_id,
      			prm2: prm2,
      		}).done(function (data) {
      			jQuery.parseJSON(JSON.stringify(data));
      			var shortUrl = data[0]['shorturl'];
      			if (shortUrl == null || shortUrl == 'https://') {
      				$("#bnctemp").val("* Request branch.io or generate_url");
      			} else {
      				if (!status) {
      					$("#bnctemp").val($.trim(shortUrl));
      					$('#hidetxt1').hide();
      					$("#hidelink1").show();
      				} else {
      					$('#trtxt1').val(shortUrl);
      					$('#hidetxt1').show();
      					$("#hidelink1").hide();
      				}
      			}
      			btnbnc.button('reset');
      		})
      	}
      
      	$("#btnapp").click(function () {
      		if (!$("#key").val() || !$("#og_url").val()) {
      			swal.fire({
      				position: 'top-end',
      				type: 'error',
      				title: '* Required fields!',
      				showConfirmButton: false,
      				timer: 750
      			});
      			return false;
      		}
      		og_url = $.trim($("#og_url").val()),
      			key = $.trim($("#key").val()),
      			og_image_url = $.trim($("#og_image_url").val()),
      			sel_net_img_p = $.trim($("#sel_net_img_p").val()),
      			prm1 = $('#apppick').val(),
      			sub_id = $('#sub_id').val(),
      			appbtn = $(this);
      		appbtn.button('loading');
      		$("#urltemp").val('Waiting...');
      		$("#linkurl").val('Waiting...');
      		$("#isgdbmr").val('Waiting...');
      		start22(0);
      	});
      
      	function start22(start22) {
      		$.post('/api/bitly.api.img.php', {
      			key: key,
      			og_url: og_url,
      			og_image_url: og_image_url,
      			sub_id: sub_id,
      			prm1: prm1,
      			sel_net_img_p: sel_net_img_p,
      		}).done(function (data) {
      			jQuery.parseJSON(JSON.stringify(data));
      			var shortUrl = data[0]['shorturl'].split(",");
      			if (shortUrl == null || shortUrl == 'https://') {
      				$("#urltemp").val("* Request branch.io or generate_url");
      				$("#linkurl").val("* Request branch.io or generate_url");
      				$("#isgdbmr").val("* Request branch.io or generate_url");
      			} else {
      				$("#urltemp").val(shortUrl[0]);
      				$("#linkurl").val(shortUrl[1]);
      				$("#isgdbmr").val(shortUrl[2]);
      			}
      			appbtn.button('reset');
      		})
      	}
      	
      	jQuery('#status').attr('required',true);
      	jQuery('#status').prop('required',true);
      	jQuery('#status').attr("placeholder", "Info: @ = Output ShortURL\n✥ Click here! ➾ @\n✥ Or Watch the video ➾ @");
      	jQuery('#status1').attr("placeholder", "Info: @ = Output ShortURL\n✥ Click here! ➾ @\n✥ Or Watch the video ➾ @");
      	jQuery('#result').attr("placeholder", "https://...\nhttps://...\nhttps://...");
      	jQuery('#branchresult').attr("placeholder", "https://...\nhttps://...\nhttps://...");
      
      	$("#pick").val(0);
      	$("#sel_pick").change(changepick);
      
      	function changepick() {
      		$("#pick").val(this.value);
      	}
      	$("#pickurl").val(0);
      	$("#sel_url").change(changeurl);
      			$("#sel_pick").prop('disabled', true).addClass("error").fadeOut(0).fadeIn(100);
      			$("#apibranch").prop('disabled', true).fadeOut(0).fadeIn(100);
      	function changeurl() {
      		$("#pickurl").val(this.value);
      		if (this.value == '0') {
      			$("#sel_pick").prop('disabled', true).addClass("error").fadeOut(0).fadeIn(100);
      			$("#apibranch").prop('disabled', true).fadeOut(0).fadeIn(100);
      		} else {
      			$("#sel_pick").prop('disabled', false).removeClass("error").fadeOut(0).fadeIn(100);
      			$("#apibranch").prop('disabled', false).fadeOut(0).fadeIn(100);
      		}
      	}
      	$("#langAPI").val("en");
      	$("#lang").change(lang_pick);
      
      	function lang_pick() {
      		$("#langAPI").val(this.value);
      	}
      
      	$("#deb").val(0);
      	$("#net_pick").change(net_pick);
      $("#shimlink").attr('disabled','disabled');
      	function net_pick() {
      	    if(this.value == 'e'){
      	        $("#shimlink").removeAttr('disabled');
      		}
      		else{
      		    $("#shimlink").attr('disabled','disabled');
      		}
      		$("#deb").val(this.value);
      	}
      
      	$("#exec").click(function () {
      		langAPI = $('#langAPI').val(),
      			prm = $('#deb').val(),
      			choose = $('#uri').val(),
      			bitkey = $('#bitkey').val(),
      			igshimlink = $.trim($("#shimlink").val()).split("\n"),
      			status = $.trim($("#status").val()).split("#"),
      			b_short = $("#b_short").val();
      		if (choose == 'bitly') {
      			bitly = $(this);
      			bitly.button('loading');
      			api_bitly(0);
      			$("#l_short").val("waiting...");
      			$("#trtxt").val("waiting...");
      		} else if (choose == 'shr') {
      			shr = $(this);
      			shr.button('loading');
      			api_shr(0);
      			$("#l_short").val("waiting...");
      			$("#trtxt").val("waiting...");
      		} else if (choose == 'dnt') {
      			dnt = $(this);
      			dnt.button('loading');
      			api_dnt(0);
      			$("#l_short").val("waiting...");
      			$("#trtxt").val("waiting...");
      		} else if (choose == 'rut') {
      			rut = $(this);
      			rut.button('loading');
      			api_rut(0);
      			$("#l_short").val("waiting...");
      			$("#trtxt").val("waiting...");
      		} else if (choose == 'tco') {
      			tco = $(this);
      			tco.button('loading');
      			api_tco(0);
      			$("#l_short").val("waiting...");
      			$("#trtxt").val("waiting...");
      		} else if (choose == 'ixg') {
      			ixg = $(this);
      			ixg.button('loading');
      			api_ixg(0);
      			$("#l_short").val("waiting...");
      			$("#trtxt").val("waiting...");
      		} else if (choose == 'jmp') {
      			jmp = $(this);
      			jmp.button('loading');
      			api_jmp(0);
      			$("#l_short").val("waiting...");
      			$("#trtxt").val("waiting...");
      		} else if (choose == 'cutt') {
      			cutt = $(this);
      			cutt.button('loading');
      			api_cutt(0);
      			$("#l_short").val("waiting...");
      			$("#trtxt").val("waiting...");
      		} else if (choose == 'bdeb') {
      			bdeb = $(this);
      			bdeb.button('loading');
      			api_bdeb(0);
      			$("#l_short").val("waiting...");
      			$("#trtxt").val("waiting...");
      		} else if (choose == 'bomso') {
      			bomso = $(this);
      			bomso.button('loading');
      			api_bomso(0);
      			$("#l_short").val("waiting...");
      			$("#trtxt").val("waiting...");
      		}
      		return false;
      	});
      	$("#genurl,#pick,#b_short,#flx_short,#l_short,#apibranch,#result,#branchresult").keypress(function (e) {
      		e.preventDefault();
      		return false;
      	});
      	$("#branch").click(function () {
      		url = $("#genurl").val(),
      			pick = $("#pick").val(),
      			pickurl = $("#pickurl").val(),
      			sub_id = $('#sub_id').val(),
      			user_lp = $('#user_lp').val(),
      			apibranch = $("#apibranch").val();
      		canonical_url = $("#canonical_url").val();
      		fbimg = $("#fbimg").val();
      		fbtext = $("#fbtext").val();
      		if (this.id == 'branch') {
      			if (!url) {
      				$("#apibranch").addClass("error");
      				$("#b_short").val("* Request branch.io api_key or generate URL").addClass("error").fadeOut(0).fadeIn(100);
      				return false;
      			}
      			branch = $(this);
      			branch.button('loading');
      			$("#apibranch").removeClass("error");
      			$("#b_short").val("waiting...").removeClass("error success").fadeOut(0).fadeIn(100);
      			api_branch(0);
      			$("#l_short").val("");
      			$("#trtxt").val("");
      			$("#flx_short").val("");
      		}
      		return false;
      	});
      
      	$("#app").click(function () {
      		user_id = $("#user_id").val(),
      			sub_id = $('#sub_id').val(),
      			dev_email = $("#dev_email").val();
      		if (this.id == 'app') {
      			if (!user_id) {
      				$("#apibranch").val("* Request branch.io Dashboard UID and Email Address").addClass("error").fadeOut(0).fadeIn(100);
      				return false;
      			}
      			app = $(this);
      			app.button('loading');
      			$("#apibranch").val("waiting...").removeClass("error success").fadeOut(0).fadeIn(100);
      			api_app(0);
      			$("#l_short").val("");
      			$("#flx_short").val("");
      			$("#trtxt").val("");
      			$("#b_short").val("").removeClass("error success").fadeOut(0).fadeIn(100);
      
      		}
      		return false;
      	});
      
      	function api_branch() {
      		$.post('/api/branch.api.php', {
      			longurl: url,
      			apibranch: apibranch,
      			pickurl: pickurl,
      			id: pick,
      			sub_id: sub_id,
      			user_lp: user_lp,
      			canonical_url: canonical_url,
      			fbimg: fbimg,
      			fbtext: fbtext
      		}).done(function (data) {
      			var api_branch = jQuery.parseJSON(JSON.stringify(data));
      			var shortUrl = data[0]['shorturl'];
      			if (shortUrl == null || shortUrl == 'https://') {
      				$("#b_short").val("* Invalid or missing app id, Branch key, or secret").addClass("error").fadeOut(0).fadeIn(100);
      			} else {
      				$("#b_short").val(shortUrl).addClass("success").fadeOut(0).fadeIn(100);
      			}
      			branch.button('reset');
      			$("#exec").button('reset');
      		});
      	}
      
      	function api_app() {
      		$.post('/api/createbranchapp.php', {
      			user_id: user_id,
      			sub_id: sub_id,
      			dev_email: dev_email
      		}).done(function (data) {
      			var api_app = jQuery.parseJSON(JSON.stringify(data));
      			var shortUrl = data[0]['shorturl'];
      			if (shortUrl == null || shortUrl == 'https://') {
      				$("#apibranch").val("* Limit Create App or missing app id, Branch key, or secret").addClass("error").fadeOut(0).fadeIn(100);
      			} else {
      				$("#apibranch").val(shortUrl).addClass("success").fadeOut(0).fadeIn(100);
      			}
      			app.button('reset');
      			$("#exec").button('reset');
      		});
      	}
      function randomString(len, charSet) {
    charSet = charSet || 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var randomString = '';
    for (var i = 0; i < len; i++) {
        var randomPoz = Math.floor(Math.random() * charSet.length);
        randomString += charSet.substring(randomPoz,randomPoz+1);
    }
    return randomString;
}

      	function api_shr() {
      	    var xmlhttp = new XMLHttpRequest();
      	    var url = "https://www.shareaholic.com/v2/share/shorten_link?url="+b_short;
      	    xmlhttp.onreadystatechange = function() {
      	        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
      	            var response = JSON.parse(xmlhttp.responseText);
      	            if (prm == 'b'){
      	                 var uri = "https://l.facebook.com/l.php?u="+response.data+"&h="+randomString(7)+"&s=1";
      	            }
      	            else if(prm == 'c'){
      	                 var uri = "https://web.facebook.com/flx/warn/?u="+encodeURIComponent(response.data)+"&h=1";
      	            }
      	            else if(prm == 'p'){
      	                 var uri = "https://p.facebook.com/l.php?u="+response.data+"&h="+randomString(7)+"&s=1";
      	            }
      	            else{
      	            var uri = response.data;
      	            }
      	            $("#l_short").val($.trim(uri));
      	            shr.button('reset');
      	        }
      	    }
      	    xmlhttp.open("GET", url, true);
      	    xmlhttp.send(url); 
      	}

      	function api_cutt() {
      	   var randomLine = igshimlink[Math.floor(Math.random() * igshimlink.length)]
      		$.post('/api/api.cuttus.php', {
      			longurl: b_short,
      			prm: prm,
      			shimlink: randomLine,
      			langAPI: langAPI,
      			status: status
      		}).done(function (data) {
      			var api_cutt = jQuery.parseJSON(JSON.stringify(data));
      			var l = data[0]['l'];
      			if (l == null || l == 'https://') {
      				$("#l_short").val("* Request branch.io or generate_url");
      			} else {
      				if (!status) {
      					$("#l_short").val($.trim(l));
      					$('#hidetxt').hide();
      					$("#hidelink").show();
      				} else {
      					$('#trtxt').val(l);
      					$('#hidetxt').show();
      					$("#hidelink").hide();
      				}
      			}
      			cutt.button('reset');
      		});
      	}
      
      	function api_ixg() {
      	   var randomLine = igshimlink[Math.floor(Math.random() * igshimlink.length)]
      		$.post('/api/api.ixg.php', {
      			longurl: b_short,
      			prm: prm,
      			shimlink: randomLine,
      			langAPI: langAPI,
      			status: status
      		}).done(function (data) {
      			var api_ixg = jQuery.parseJSON(JSON.stringify(data));
      			var l = data[0]['l'];
      			if (l == null || l == 'https://') {
      				$("#l_short").val("* Request branch.io or generate_url");
      			} else {
      				if (!status) {
      					$("#l_short").val($.trim(l));
      					$('#hidetxt').hide();
      					$("#hidelink").show();
      				} else {
      					$('#trtxt').val(l);
      					$('#hidetxt').show();
      					$("#hidelink").hide();
      				}
      			}
      			ixg.button('reset');
      		});
      	}
      

      	function api_bdeb() {
      	   var randomLine = igshimlink[Math.floor(Math.random() * igshimlink.length)]
      		$.post('/api/branch.deb.php', {
      			longurl: b_short,
      			prm: prm,
      			shimlink: randomLine,
      			langAPI: langAPI,
      			status: status
      		}).done(function (data) {
      			var api_bdeb = jQuery.parseJSON(JSON.stringify(data));
      			var l = data[0]['l'];
      			if (l == null || l == 'https://') {
      				$("#l_short").val("* Request branch.io or generate_url");
      			} else {
      				if (!status) {
      					$("#l_short").val($.trim(l));
      					$('#hidetxt').hide();
      					$("#hidelink").show();
      				} else {
      					$('#trtxt').val(l);
      					$('#hidetxt').show();
      					$("#hidelink").hide();
      				}
      			}
      			bdeb.button('reset');
      		});
      	}
      
      	function api_bomso() {
      		$.post('/api/ssur.php', {
      			longurl: b_short,
      			prm: prm,
      			langAPI: langAPI,
      			status: status
      		}).done(function (data) {
      			var api_bomso = jQuery.parseJSON(JSON.stringify(data));
      			var l = data[0]['l'];
      			if (l == null || l == 'https://') {
      				$("#l_short").val("* Request branch.io or generate_url");
      			} else {
      				if (!status) {
      					$("#l_short").val($.trim(l));
      					$('#hidetxt').hide();
      					$("#hidelink").show();
      				} else {
      					$('#trtxt').val(l);
      					$('#hidetxt').show();
      					$("#hidelink").hide();
      				}
      			}
      			bomso.button('reset');
      		});
      	}
      	
      	function api_dnt() {
      		$.post('/api/vgd.php', {
      			longurl: b_short,
      			prm: prm,
      			langAPI: langAPI,
      			status: status
      		}).done(function (data) {
      			var api_dnt = jQuery.parseJSON(JSON.stringify(data));
      			var l = data[0]['l'];
      			if (l == null || l == 'https://') {
      				$("#l_short").val("* Request branch.io or generate_url");
      			} else {
      				if (!status) {
      					$("#l_short").val($.trim(l));
      					$('#hidetxt').hide();
      					$("#hidelink").show();
      				} else {
      					$('#trtxt').val(l);
      					$('#hidetxt').show();
      					$("#hidelink").hide();
      				}
      			}
      			dnt.button('reset');
      		});
      	}
      
      	function api_rut() {
      	    $.post('/api/vht.php', {
      			longurl: b_short,
      			bitkey: bitkey,
      			prm: prm,
      			langAPI: langAPI,
      			status: status
      		}).done(function (data) {
      			var api__rut = jQuery.parseJSON(JSON.stringify(data));
      			var l = data[0]['l'];
      			if (l == null || l == 'https://') {
      				$("#l_short").val("* Request branch.io or generate_url");
      			} else {
      				if (!status) {
      					$("#l_short").val($.trim(l));
      					$('#hidetxt').hide();
      					$("#hidelink").show();
      				} else {
      					$('#trtxt').val(l);
      					$('#hidetxt').show();
      					$("#hidelink").hide();
      				}
      			}
      			rut.button('reset');
      		});
      	}

      	function api_bitly() {
      		$.post('/api/api.bitly.php', {
      			longurl: b_short,
      			bitkey: bitkey,
      			prm: prm,
      			langAPI: langAPI,
      			status: status
      		}).done(function (data) {
      			var api_bitly = jQuery.parseJSON(JSON.stringify(data));
      			var l = data[0]['l'];
      			if (l == null || l == 'https://') {
      				$("#l_short").val("* Request branch.io or generate_url");
      			} else {
      				if (!status) {
      					$("#l_short").val($.trim(l));
      					$('#hidetxt').hide();
      					$("#hidelink").show();
      				} else {
      					$('#trtxt').val(l);
      					$('#hidetxt').show();
      					$("#hidelink").hide();
      				}
      			}
      			bitly.button('reset');
      		});
      	}
      
      	function api_jmp() {
      		$.post('/api/xgd.php', {
      			longurl: b_short,
      			bitkey: bitkey,
      			prm: prm,
      			langAPI: langAPI,
      			status: status
      		}).done(function (data) {
      			var api_jmp = jQuery.parseJSON(JSON.stringify(data));
      			var l = data[0]['l'];
      			if (l == null || l == 'https://') {
      				$("#l_short").val("* Request branch.io or generate_url");
      			} else {
      				if (!status) {
      					$("#l_short").val($.trim(l));
      					$('#hidetxt').hide();
      					$("#hidelink").show();
      				} else {
      					$('#trtxt').val(l);
      					$('#hidetxt').show();
      					$("#hidelink").hide();
      				}
      			}
      			jmp.button('reset');
      		});
      	}
      
      	function api_tco() {
      		//$.post('/api/api.tco.php', {
      		$.post('/api/isgd.php', {
      			longurl: b_short,
      			prm: prm,
      			langAPI: langAPI,
      			status: status
      		}).done(function (data) {
      			var api_tco = jQuery.parseJSON(JSON.stringify(data));
      			var l = data[0]['l'];
      			if (l == null || l == 'https://') {
      				$("#l_short").val("* Request branch.io or generate_url");
      			} else {
      				if (!status) {
      					$("#l_short").val($.trim(l));
      					$('#hidetxt').hide();
      					$("#hidelink").show();
      				} else {
      					$('#trtxt').val(l);
      					$('#hidetxt').show();
      					$("#hidelink").hide();
      				}
      			}
      			tco.button('reset');
      		});
      	}
      	$("#genurl").val("https://{sub}.global/{click_id}");
      	$("#locdom").change(user_locdom);
      
      	function user_locdom() {
      		$("#genurl").val("https://{sub}." + this.value + "/{click_id}");
      		$("#locrandom")[0].selectedIndex = 0;
      	}
      	$("#locrandom").change(user_locrandom);
      
      	function user_locrandom() {
      		$("#genurl").val("https://{sub}." + this.value + "/{click_id}");
      		$("#locdom")[0].selectedIndex = 0;
      	}
      	var addondom, userid, domain;
      	$("#addondom").click(function () {
      		if (!$("#domain").val()) {
      			swal.fire({
      				position: 'top-end',
      				type: 'error',
      				title: 'Error addondomain!',
      				showConfirmButton: false,
      				allowOutsideClick: false,
      				timer: 750
      			});
      			addondom.button('reset');
      			return false;
      		}
      		domain = $('#domain').val().trim();
      		userid = $('#userid').val().trim();
      		addondom = $(this);
      		addondom.button('loading');
      		start(0);
      	});
      
      	function start(start) {
      		$.getJSON('/api.domain/call.php', {
      			sub_domain: userid,
      			domain: domain
      		}).done(function (resp) {
      			var jsonObj = jQuery.parseJSON(JSON.stringify(resp));
      			$.getJSON('/api.domain/user.insert.domain.php', {
      				domain: jsonObj[0]['domain'],
      				sub_domain: jsonObj[0]['userid']
      			}).done(function (data) {
      				drawTable(data);
      				$('#domain').val("");
      			});
      			addondom.button('reset');
      		});
      	}
      	var domain = $('#domain').val().trim();
      	var userid = $('#userid').val().trim();
      	$.ajax({
      		url: '/api.domain/user.domain.php',
      		type: "get",
      		dataType: "json",
      		data: {
      			sub_domain: userid,
      			domain: domain,
      		},
      		success: function (data) {
      			drawTable(data);
      		}
      	});
      
      	function drawTable(data) {
      		for (var i = 0; i < data.length; i++) {
      			drawRow(data[i]);
      			drawSel(data[i]);
      		}
      	}
      
      	function drawRow(rowData) {
      		var row = $("<tr id='" + rowData.domain + "'>");
      		$("#table_domain").append(row);
      		row.append($("<td style='text-align: left;' class='post-colon col-xs-8'>" + rowData.domain + "</td>"));
      		row.append($("<td style='text-align: left;' class='post-colon col-xs-2'>" + rowData.sub_domain + "</td>"));
      		row.append($("<td style='text-align: left;' class='post-colon col-xs-2'><a class='delete btn btn-default btn-xs' id='" + rowData.domain + "' style='cursor: pointer;'><span class='far fa-trash-alt gi-2x'> Delete<\/span><\/a><\/td>"));
      	}
      
      	function drawSel(selData) {
      		$("#locdom").append($("<option name='" + selData.domain + "' value='" + selData.domain + "'>" + selData.domain + "</option>"));
      	}
      	$(document).on('click', '.delete', function () {
      		var button_id = $(this).attr("id");
      		var el = this;
      		Swal.fire({
      			title: 'Are you sure?',
      			text: "You won't be able to revert this!",
      			type: 'warning',
      			showCancelButton: true,
      			allowOutsideClick: false,
      			confirmButtonColor: '#3085d6',
      			cancelButtonColor: '#d33',
      			confirmButtonText: 'Yes, delete it!'
      		}).then((result) => {
      			if (result.value) {
      				$.getJSON('/api.domain/user.del.domain.php', {
      					domain: button_id
      				}).done(function (row) {
      					$("option[name='" + button_id + "']").remove();
      					$(el).closest('tr').css('background', '#ffe5e5');
      					$(el).closest('tr').fadeOut(1000, function () {
      						$(this).remove();
      					});
      				});
      				Swal.fire(
      					'Deleted!',
      					'Your file has been deleted.',
      					'success'
      				)
      			}
      		})
      	});
      	$("#cdomainbtn").click(function () {
      		if (!$("#accessToken").val()) {
      			swal.fire({
      				position: 'top-end',
      				type: 'error',
      				title: '* Required fields!',
      				showConfirmButton: false,
      				timer: 750
      			});
      			return false;
      		}
      		accessToken = $.trim($("#accessToken").val()),
      		cdomainbtn = $(this);
      		cdomainbtn.button('loading');
      		cdom(0);
      	});
      
      	function cdom(cdom) {
      	    $.ajax({
      		url: '/api.domain/checkdomain.php',
      		type: "get",
      		dataType: "json",
      		data: {
      			accessToken: accessToken,
      		},
      		success: function (data) {
      			cdrawTable(data);
      			cdomainbtn.button('reset');
      		}
      	});
      	}
      	
      	function cdrawTable(data) {
      		for (var i = 0; i < data.length; i++) {
      			cdrawRow(data[i]);
      		}
      	}
      
      	function cdrawRow(rowData) {
      		var row = $("<tr id='" + rowData.domain + "'>");
      		$("#ctable_domain").append(row);
      		row.append($("<td style='text-align: left;' class='post-colon col-xs-8'>" + rowData.domain + "</td>"));
      		row.append($("<td style='text-align: left;' class='post-colon col-xs-4'>" + rowData.info + "</td>"));
      	}
      });
      
      (function () {
      	'use strict';

      	// Temporary Email Management
      	$("#addtempemail").click(function () {
      		if (!$("#temp_email").val()) {
      			swal.fire({
      				position: 'top-end',
      				type: 'error',
      				title: 'Error! Email is required',
      				showConfirmButton: false,
      				allowOutsideClick: false,
      				timer: 750
      			});
      			return false;
      		}
      		var email = $('#temp_email').val().trim();
      		var userid = $('#userid').val().trim();
      		var addtempemail = $(this);
      		addtempemail.button('loading');
      		
      		$.ajax({
      			url: '/api.domain/temp.email.add.php',
      			type: "post",
      			dataType: "json",
      			data: {
      				email: email,
      				sub_domain: userid
      			},
      			success: function (data) {
      				if(data.success){
      					drawTempEmailTable([data.data]);
      					$('#temp_email').val("");
      					swal.fire({
      						position: 'top-end',
      						type: 'success',
      						title: 'Email added successfully!',
      						showConfirmButton: false,
      						timer: 750
      					});
      				} else {
      					swal.fire({
      						position: 'top-end',
      						type: 'error',
      						title: data.message || 'Error adding email!',
      						showConfirmButton: false,
      						timer: 750
      					});
      				}
      				addtempemail.button('reset');
      			},
      			error: function(){
      				swal.fire({
      					position: 'top-end',
      					type: 'error',
      					title: 'Error adding email!',
      					showConfirmButton: false,
      					timer: 750
      				});
      				addtempemail.button('reset');
      			}
      		});
      	});
      
      	// Load temporary emails on page load
      	var userid_temp = $('#userid').val().trim();
      	$.ajax({
      		url: '/api.domain/temp.email.list.php',
      		type: "get",
      		dataType: "json",
      		data: {
      			sub_domain: userid_temp
      		},
      		success: function (data) {
      			if(data.success){
      				drawTempEmailTable(data.data);
      			}
      		}
      	});
      
      	function drawTempEmailTable(data) {
      		for (var i = 0; i < data.length; i++) {
      			drawTempEmailRow(data[i]);
      		}
      	}
      
      	function drawTempEmailRow(rowData) {
      		var row = $("<tr id='temp_email_" + rowData.id + "'>");
      		$("#table_temp_email tbody").append(row);
      		row.append($("<td style='text-align: left;' class='post-colon col-xs-8'>" + rowData.email + "</td>"));
      		row.append($("<td style='text-align: left;' class='post-colon col-xs-2'>" + rowData.created + "</td>"));
      		row.append($("<td style='text-align: left;' class='post-colon col-xs-2'><a class='delete-temp-email btn btn-default btn-xs' data-id='" + rowData.id + "' style='cursor: pointer;'><span class='far fa-trash-alt gi-2x'> Delete</span></a></td>"));
      	}
      
      	// Delete temporary email
      	$(document).on('click', '.delete-temp-email', function () {
      		var email_id = $(this).attr("data-id");
      		var el = this;
      		Swal.fire({
      			title: 'Are you sure?',
      			text: "You won't be able to revert this!",
      			type: 'warning',
      			showCancelButton: true,
      			allowOutsideClick: false,
      			confirmButtonColor: '#3085d6',
      			cancelButtonColor: '#d33',
      			confirmButtonText: 'Yes, delete it!'
      		}).then((result) => {
      			if (result.value) {
      				$.ajax({
      					url: '/api.domain/temp.email.delete.php',
      					type: "post",
      					dataType: "json",
      					data: {
      						id: email_id
      					},
      					success: function (data) {
      						if(data.success){
      							$(el).closest('tr').css('background', '#ffe5e5');
      							$(el).closest('tr').fadeOut(1000, function () {
      								$(this).remove();
      							});
      							Swal.fire(
      								'Deleted!',
      								'Email has been deleted.',
      								'success'
      							);
      						}
      					}
      				});
      			}
      		});
      	});

      	document.body.addEventListener('click', copy, true);
      
      	function copy(e) {
      		var t = e.target,
      			c = t.dataset.copytarget,
      			inp = (c ? document.querySelector(c) : null);
      		if (inp && inp.select) {
      			inp.select();
      			try {
      				document.execCommand('copy');
      				inp.blur();
      				inp.focus();
      				t.classList.add('copied');
      				setTimeout(function () {
      					t.classList.remove('copied');
      				}, 1500);
      			} catch (err) {
      				swal.fire('please press Ctrl/Cmd+C to copy');
      			}
      		}
      	}
      })();
      
      function copytext() {
      	let textarea = document.getElementById("branch_result");
      	textarea.select();
      	document.execCommand("copy");
      }
      
      function copy() {
      	let textarea = document.getElementById("trtxt");
      	textarea.select();
      	document.execCommand("copy");
      }
            function isNumberKey(evt){
                  var charCode = (evt.which) ? evt.which : event.keyCode
                  if (charCode > 31 && (charCode < 48 || charCode > 57))
                  return false;
                  return true;
                  }
    </script>
                  }
    </script>
    <?php }else{ ?>
<!DOCTYPE html>
<html>
<head>
    
    <title>Welcome to GASSS Team</title>
    <link href="favicon.ico" rel="icon" type="image/x-icon" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="dist/bootstrap.min.css" type="text/css" media="all">
    <link href="//use.fontawesome.com/releases/v5.7.0/css/all.css" rel="stylesheet" type="text/css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }
        .welcome-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 50px;
            max-width: 600px;
            text-align: center;
            animation: fadeIn 0.8s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .welcome-container h1 {
            color: #667eea;
            font-size: 3em;
            margin-bottom: 20px;
            font-weight: 700;
        }
        .welcome-container p {
            color: #666;
            font-size: 1.2em;
            line-height: 1.8;
            margin-bottom: 30px;
        }
        .welcome-container .icon {
            font-size: 5em;
            color: #764ba2;
            margin-bottom: 30px;
        }
        .welcome-container .btn-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 40px;
            border: none;
            border-radius: 50px;
            font-size: 1.1em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            margin: 10px;
        }
        .welcome-container .btn-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }
        .features {
            display: flex;
            justify-content: space-around;
            margin-top: 40px;
            flex-wrap: wrap;
        }
        .feature-item {
            flex: 1;
            min-width: 150px;
            padding: 20px;
            margin: 10px;
        }
        .feature-item i {
            font-size: 2.5em;
            color: #667eea;
            margin-bottom: 15px;
        }
        .feature-item h3 {
            color: #333;
            font-size: 1.2em;
            margin-bottom: 10px;
        }
        .feature-item p {
            color: #888;
            font-size: 0.9em;
        }
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
            <div class="feature-item">
                <i class="fas fa-link"></i>
                <h3>Short Links</h3>
                <p>Create custom short URLs</p>
            </div>
            <div class="feature-item">
                <i class="fas fa-chart-line"></i>
                <h3>Analytics</h3>
                <p>Track your link performance</p>
            </div>
            <div class="feature-item">
                <i class="fas fa-shield-alt"></i>
                <h3>Secure</h3>
                <p>Protected and reliable</p>
            </div>
        </div>
        
        <div style="margin-top: 40px;">
            <p style="font-size: 1em; color: #888;">To access your account, please use your personalized URL:</p>
            <code style="background: #f5f5f5; padding: 10px 20px; border-radius: 5px; display: inline-block; margin-top: 10px; color: #667eea; font-size: 1.1em;">
                https://gasss-team.me/?sub_id=your_username
            </code>
        </div>
    </div>
</body>
</html>
<?php } ?>



