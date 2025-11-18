<?php
error_reporting(0);
include_once('../password.login.php');
include_once("../../xmlapi.php");
include_once('../connection.config.php');

ob_end_clean();
header("Connection: close\r\n");
header("Content-Encoding: none\r\n");
ignore_user_abort(true);

$LOGIN_INFORMATION = array(
  '' => ADMIN_PASSWORD
);

/**
 * NOTE:
 * addondom() will be triggered by frontend GET to this same page:
 * the frontend does: $.getJSON('', { url: addon }, callback)
 * So keep behaviour compatible with your previous usage.
 */
function addondom($url) {
    $curl = curl_init();

    // prepare minimal post (was referencing $post which didn't exist)
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt ($curl, CURLOPT_POST, TRUE);
    curl_setopt ($curl, CURLOPT_POSTFIELDS, []);
    curl_setopt($curl, CURLOPT_TIMEOUT, 1);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl,  CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_FORBID_REUSE, true);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 1);
    curl_setopt($curl, CURLOPT_DNS_CACHE_TIMEOUT, 10);
    curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
    @curl_exec($curl);
    @curl_close($curl);

    // cPanel credentials from original file (you had them in the snippet)
    $cpanelUser = 'gassstea';
    $cpanelPass = 'ksw30089ksw30089ksw30089';

    $XmlApi = new xmlapi('localhost');
    $XmlApi->set_port(2083);
    $XmlApi->set_output('json');
    $XmlApi->password_auth($cpanelUser, $cpanelPass);
    $XmlApi->set_debug(0);

    // Park domain (legacy behavior)
    $result = $XmlApi->api2_query($cpanelUser, 'Park', 'park', [
        'domain'      => $url
    ]);

    header ("Content-type: application/json");
    if($result != null){
        // create wildcard subdomain on success
        $XmlApi->api2_query($cpanelUser, 'SubDomain', 'addsubdomain', [
           'domain'      => '*',
           'rootdomain'  => $url,
           'dir'         => '/gasss-team'
        ]);
        echo json_encode(['ok' => true, 'domain' => $url]);
    } else {
        echo json_encode(['ok' => false, 'domain' => $url]);
    }
    exit();
}

function getContent($url) {
    $content = '';

    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $content = @curl_exec($ch);
        @curl_close($ch);
    } elseif (ini_get('allow_url_fopen')) {
        $content = @file_get_contents($url);
    }

    return $content;
}

/* If frontend calls ?url=... to trigger cPanel action */
if (!empty($_GET['url'])) {
    $url = trim($_GET['url']);
    addondom($url);
    // addondom exits after output
    exit();
}
?>
<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>ADMIN PANEL</title>
    <link href="/favicon.ico" rel="icon" type="image/x-icon" />
    <link rel="stylesheet" href="../dist/bootstrap.min.css" type="text/css" media="all">
    <link href="../dist/jquery.bootgrid.css" rel="stylesheet" />
    <script src="../dist/jquery-1.11.1.min.js"></script>
    <script src="../dist/bootstrap.min.js"></script>
    <script src="../dist/jquery.bootgrid.min.js"></script>
    <link href="//fonts.googleapis.com/css?family=Open+Sans:400,600" rel="stylesheet" type="text/css">
    <link href="//use.fontawesome.com/releases/v5.7.0/css/all.css" rel="stylesheet" type="text/css">
    <script src="//unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@8"></script>
    <style>
    body { padding-top: 70px; font-family:consolas; }
    .or { display:flex; justify-content:center; align-items: center; color:grey; }
    .or:after, .or:before { content: ""; display: block; background: grey; width: 100%; height:1px; margin: 0 10px; }
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
                     allowOutsideClick: false,
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
<div role="navigation" class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button data-target=".navbar-collapse" data-toggle="collapse" class="navbar-toggle" type="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
            </button>
            <a href="#" class="navbar-brand"><strong>Admin Panel</strong></a>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
            <li><a href="/admin.panel/"><strong>Dashboard</strong></a></li>
            <li><a href="/admin.campign/"><strong>Campaigns</strong></a></li>
            <li class="active"><a href="#"><strong>Addon Domian</strong></a></li>
            </ul>
        </div>
    </div>
</div>

<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <code></code>
            <div class="pull-right">
                <button type="button" class="btn btn-xs btn-primary" id="command-add" data-row-id="0">
                    <span class="glyphicon glyphicon-plus"></span> Addon Domain </button>
            </div>
        </div>
        <div class="panel-body">
            <table id="gen_grid" class="table table-bordered table-hover table-striped table-condensed" cellspacing="0" data-toggle="bootgrid" style="table-layout: auto;">
                <thead>
                    <tr>
                        <th data-column-id="id" data-type="numeric" data-identifier="true">Empid</th>
                        <th class="col-md-auto" data-column-id="sub_domain">Domain Title</th>
                        <th data-column-id="domain">Domain Name</th>
                        <th data-column-id="commands" data-formatter="commands" data-sortable="false">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- ADD modal: modified for multi-add -->
<div id="add_model" class="modal fade" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Addon Domain</h4>
      </div>

      <div class="modal-body">
        <blockquote>
          <p class="text-warning">Nameserver:</p>
          <footer class="text-muted">
            ns1-ns4.mysecurecloudhost.com
          </footer>
        </blockquote>

        <form method="post" id="frm_add">
          <input type="hidden" value="add" name="action" id="action">
          <div class="form-group">
            <label class="control-label">Domain Title: use [GLOBAL DOMAIN] for submit into global domain</label>
            <hr style="margin:1px;padding:1px;border:0;border-bottom:0px">
            <select id="adddomain" class="form-control input-sm" type="button">
              <optgroup label="ADDON DOMAIN">
                <option value="0" disabled class="bold-option">---ADDON DOMAIN---</option>
                <option value="global" selected="selected" class="bold-option">[GLOBAL DOMAIN]</option>
                <?php $result = mysqli_query($link, "SELECT * FROM generate");
                while($row = mysqli_fetch_array($result)) {
                  echo '<option value="'.$row['sub_id'].'">'.$row['sub_id'].'</option>';
                }?>
              </optgroup>
            </select>
            <input readonly="readonly" type="hidden" class="form-control" id="sub_domain" name="sub_domain" required="true" />
          </div>

          <div class="form-group">
            <label class="control-label">Domain(s) (maks 10). Pisahkan dengan newline atau koma:</label>
            <textarea placeholder="example.com
another-example.id" class="form-control" id="domain_list" name="domain_list" rows="4" required></textarea>
            <input type="hidden" id="domain" name="domain" />
            <small class="text-muted">Contoh: one.com, two.id atau
one.com newline two.id</small>
          </div>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" id="btn_add" class="btn btn-primary">Save</button>
      </div>
    </div>
  </div>
</div>

<!-- EDIT modal unchanged except minor IDs -->
<div id="edit_model" class="modal fade" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Edit Domain</h4>
            </div>
            <div class="modal-body">
                <form method="post" id="frm_edit">
                    <input type="hidden" value="edit" name="action" id="action">
                    <input type="hidden" value="0" name="edit_id" id="edit_id">

                    <div class="form-group">
                        <label for="salary" class="control-label">Domain Title:</label>
                        <input readonly="readonly" type="text" class="form-control" id="edit_sub_domain" name="edit_sub_domain" />
                    </div>
                    <div class="form-group">
                        <label for="salary" class="control-label">Domain Name:</label>
                        <input type="text" class="form-control" id="edit_domain" name="edit_domain" required="true" autofocus/>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="btn_edit" class="btn btn-primary">Save</button>
            </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $("#adddomain").change(admin_adddomain);
    $("#sub_domain").val('global');
    function admin_adddomain() { $("#sub_domain").val(this.value); }

    var grid = $("#gen_grid").bootgrid({
        ajax: true,
        rowSelect: true,
        caseSensitive   : false,
        rowCount        : [25, 50, -1],
        columnSelection : false,
        post: function() {
            return { id: "b0df282a-0d67-40e5-8558-c9e93b7befed" };
        },
        url: "response.php",
        formatters: {
            "commands": function(column, row) {
                return "<button disabled type=\"button\" class=\"btn btn-xs btn-default command-edit\" data-row-id=\"" + row.id + "\"><span class=\"glyphicon glyphicon-edit\"></span></button> " +
                    "<button type=\"button\" class=\"btn btn-xs btn-default command-delete\" data-row-id=\"" + row.id + "\"><span class=\"glyphicon glyphicon-trash\"></span></button>";
            }
        }
    }).on("loaded.rs.jquery.bootgrid", function() {
        grid.find(".command-edit").on("click", function(e) {
            var ele = $(this).parent();
            var g_id = $(this).parent().siblings(':first').html();
            var g_name = $(this).parent().siblings(':nth-of-type(2)').html();
            $('#edit_model').modal('show');
            if ($(this).data("row-id") > 0) {
                $('#edit_id').val(ele.siblings(':first').html());
                $('#edit_sub_domain').val(ele.siblings(':nth-of-type(2)').html());
                $('#edit_domain').val(ele.siblings(':nth-of-type(3)').html());
            } else {
                alert('Now row selected! First select row, then click edit button');
            }
        }).end().find(".command-delete").on("click", function(e) {
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
                    $.post('response.php', {
                        id: $(this).data("row-id"),
                        action: 'delete'
                        }, function(resp) {
                            $("#gen_grid").bootgrid('reload');
                        }, 'json');
                        Swal.fire('Deleted!', 'Your file has been deleted.', 'success')
                }});
        });
    });

    function postSingleDomain(domain, sub_domain) {
        var data = { action: 'add', sub_domain: sub_domain, domain: domain };
        return $.ajax({ type: "POST", url: "response.php", data: data, dataType: "json" });
    }

    function normalizeDomains(raw) {
        if (!raw) return [];
        var parts = raw.split(/[\r\n,]+/);
        var out = [];
        for (var i=0;i<parts.length;i++){
            var d = parts[i].trim();
            if (d.length) out.push(d);
        }
        return out;
    }

    $("#command-add").click(function() {
        $("#domain_list").val("");
        $('#add_model').modal('show');
    });

    $("#btn_add").off('click').on('click', function() {
        var raw = $("#domain_list").val().trim();
        var sub = $("#sub_domain").val() || 'global';

        if (!raw) {
            Swal.fire({ allowOutsideClick: false, type: 'error', title: 'Oops...', text: 'Masukkan minimal 1 domain' });
            return false;
        }

        var domains = normalizeDomains(raw);
        domains = domains.filter(function(it, idx) { return domains.indexOf(it) === idx; });

        if (domains.length === 0) {
            Swal.fire({ allowOutsideClick: false, type: 'error', title: 'Oops...', text: 'Tidak ada domain valid' });
            return false;
        }
        if (domains.length > 10) {
            Swal.fire({ allowOutsideClick: false, type: 'error', title: 'Limit terlampaui', text: 'Maksimal 10 domain per submit' });
            return false;
        }

        var invalid = domains.filter(function(d){
            return !/^[a-z0-9\-]+(\.[a-z0-9\-]+)+$/i.test(d);
        });
        if (invalid.length) {
            Swal.fire({ allowOutsideClick: false, type: 'error', title: 'Format domain salah', text: 'Domain tidak valid: ' + invalid.slice(0,5).join(', ') + (invalid.length>5? '...' : '') });
            return false;
        }

        Swal.fire({ title: 'Processing', html: 'Menambahkan ' + domains.length + ' domain. Tunggu sebentar...', allowOutsideClick: false, onBeforeOpen: () => { Swal.showLoading(); } });

        var requests = [];
        for (var i=0;i<domains.length;i++){ requests.push(postSingleDomain(domains[i], sub)); }

        $.when.apply($, requests).done(function() {
            Swal.close();
            $('#add_model').modal('hide');
            $("#domain_list").val('');
            $("#gen_grid").bootgrid('reload');
            Swal.fire({ type: 'success', title: 'Selesai', text: domains.length + ' domain berhasil ditambahkan' });
            // also trigger cPanel park action for each domain (non-blocking)
            try {
                domains.forEach(function(d) {
                    $.getJSON('', { url: d }, function(data){ console.log('cPanel action:', d, data); });
                });
            } catch(e) { console.log(e); }
        }).fail(function(jqXHR, textStatus, errorThrown){
            Swal.close();
            $('#add_model').modal('hide');
            $("#gen_grid").bootgrid('reload');
            Swal.fire({ type: 'error', title: 'Sebagian / semua gagal', text: 'Cek log server atau coba lagi. error: ' + (errorThrown || textStatus) });
        });
    });

    $("#btn_edit").click(function() {
        var data = $("#frm_edit").serialize();
        $.post('response.php', data, function(resp){
            if (resp && resp.ok) {
                $('#edit_model').modal('hide');
                $("#gen_grid").bootgrid('reload');
                Swal.fire({ type: 'success', title: 'Updated', text: resp.message || 'Domain updated' });
            } else {
                Swal.fire({ type: 'error', title: 'Failed', text: resp.message || 'Update failed' });
            }
        }, 'json');
    });

});
</script>
</body>
</html>
