<?php include_once('../login.php');
$page_name = dirname(__FILE__);
$each_page_name = explode('/', $page_name);
$data = explode(".",end($each_page_name));
$team=strtoupper($data[0]);
?>
<div role="navigation" class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button data-target=".navbar-collapse" data-toggle="collapse" class="navbar-toggle" type="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="#" class="navbar-brand"><strong>Admin Panel</strong></a>
            </div>
            <div class="navbar-collapse collapse navbar-right">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="#"><strong>Dashboard</strong></a></li>
                    <li><a href="/admin.campign/"><strong>Campaigns</strong></a></li>
                    <li><a href="/admin.domain/"><strong>Addon Domain</strong></a></li>
                </ul>
            </div>
            <!--/.nav-collapse -->
        </div>
    </div>
    <div class="container table-responsive">
        <div class="panel panel-default">
            <div class="panel-heading">
                <code></code>
                <div class="pull-right">
                    <button type="button" class="btn btn-xs btn-primary" id="command-add" data-row-id="0">
                        <span class="glyphicon glyphicon-plus"></span> Create Generate</button>
                </div>
            </div>
            <div class="panel-body">

                    <table id="gen_grid" class="table table-bordered table-hover table-striped table-condensed" cellspacing="0" data-toggle="bootgrid" style="table-layout: auto;">
                    <thead>
                        <tr>
                            <th data-column-id="id" data-type="numeric" data-identifier="true">Empid</th>
                            <th data-column-id="sub_id">Tracker</th>
                            <th data-column-id="password">Password</th>
                            <th data-column-id="gen_url">Generate URL</th>
                            <th data-column-id="sm_url">TEAM</th>
                            <th data-align="right" data-column-id="commands" data-formatter="commands" data-sortable="false">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    </div>
    <div id="add_model" class="modal fade" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Create Generate</h4>
                </div>
                <div class="modal-body">
                    <blockquote>
                    <p class="text-warning">ADD UserID:</p>
                    <!--<footer class="text-muted">Pastikan tidak ada special characters: <font style="color:#ea4335;">" !"#$%&'()*+,-./:;<=>?@[\]^_`{|}~"</font></footer>--> 
                    </blockquote> 
                    <form method="post" id="frm_add">
                        <input type="hidden" value="add" name="action" id="action">
                        <div class="form-group">
                            <label for="salary" class="control-label">Tracker:</label>
                            <input type="text" placeholder="{tracker}" class="form-control" id="sub_id" name="sub_id" required="true" autofocus="" autocomplete="off"/>
                        </div>
                        <div class="form-group">
                            <label for="salary" class="control-label">Password:</label>
                            <input type="text" placeholder="{password}" class="form-control" id="password" name="password" required="true"/>
                        </div>
                        <div class="form-group">
                            <label for="salary" class="control-label">Generate URL:</label>
                            <input readonly="readonly" type="text" class="form-control" id="gen_url" name="gen_url" placeholder="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? " https" : "http") . "://{$_SERVER[ 'HTTP_HOST']}/"; ?>"/>
                        </div>
                        <div class="form-group">
                            <label for="salary" class="control-label">TEAM:</label>
                            <input type="text" placeholder="{smartlink}" class="form-control" id="sm_url" name="sm_url" required="true" autocomplete="off" readonly/>
                        </div>
                        </div>
                        <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" id="btn_add" class="btn btn-primary">Save</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <div id="edit_model" class="modal fade" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Edit Generate</h4>
                </div>
                <div class="modal-body">
                    <form method="post" id="frm_edit">
                        <input type="hidden" value="edit" name="action" id="action">
                        <input type="hidden" value="0" name="edit_id" id="edit_id">
                        <div class="form-group">
                            <label for="salary" class="control-label">Tracker:</label>
                            <input type="text" class="form-control" id="edit_sub_id" name="edit_sub_id" required="true" autofocus autocomplete="off"/>
                        </div>
                        <div class="form-group">
                            <label for="salary" class="control-label">Password:</label>
                            <input type="text" class="form-control" id="edit_password" name="edit_password" />
                        </div>
                        <div class="form-group">
                            <label for="salary" class="control-label">Generate URL:</label>
                            <input type="text" class="form-control" id="edit_gen_url" name="edit_gen_url" readonly="readonly"/>
                        </div>
                        <div class="form-group">
                            <label for="salary" class="control-label">TEAM:</label>
                            <input type="text" class="form-control" id="edit_sm_url" name="edit_sm_url" autocomplete="off" readonly/>
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
            var serv = "<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? " https" : "http") . "://{$_SERVER[ 'HTTP_HOST']}/"; ?>";
            var inputBox = document.getElementById('sub_id');
            inputBox.onkeyup = function(){
                document.getElementById('gen_url').value = serv+inputBox.value.toUpperCase();
            }
            var edit_inputBox = document.getElementById('edit_sub_id');
            edit_inputBox.onkeyup = function(){
                document.getElementById('edit_gen_url').value = serv+edit_inputBox.value.toUpperCase();
            }
            var grid = $("#gen_grid").bootgrid({
                ajax: true,
                sort: true,
                search: true,
                rowSelect: true,
                caseSensitive   : false,
                rowCount        : [25, 50, -1],
                columnSelection : false,
                post: function() {
                    return {
                        id: "b0df282a-0d67-40e5-8558-c9e93b7befed"
                    };
                },

                url: "response.php",
                formatters: {
                    "commands": function(column, row) {
                        return "<button type=\"button\" class=\"btn btn-xs btn-default command-edit\" data-row-id=\"" + row.id + "\"><span class=\"glyphicon glyphicon-edit\"></span></button> " +
                            "<button type=\"button\" class=\"btn btn-xs btn-default command-delete\" data-row-id=\"" + row.id + "\"><span class=\"glyphicon glyphicon-trash\"></span></button>";
                    }
                }
            }).on("loaded.rs.jquery.bootgrid", function() {
                grid.find(".command-edit").on("click", function(e) {
                    var ele = $(this).parent();
                    var g_id = $(this).parent().siblings(':first').html();
                    var g_name = $(this).parent().siblings(':nth-of-type(2)').html();
                    console.log(g_id);
                    console.log(g_name);

                    $('#edit_model').modal('show');
                    if ($(this).data("row-id") > 0) {
                        $('#edit_id').val(ele.siblings(':first').html());
                        $('#edit_sub_id').val(ele.siblings(':nth-of-type(2)').html());
                        $('#edit_password').val(ele.siblings(':nth-of-type(3)').html());
                        $('#edit_gen_url').val(ele.siblings(':nth-of-type(4)').html());
                        $('#edit_sm_url').val(ele.siblings(':nth-of-type(5)').html());
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
                                }, function() {
                                    $("#gen_grid").bootgrid('reload');
                                    });
                                    Swal.fire(
                                        'Deleted!',
                                        'Your file has been deleted.',
                                        'success'
                                        )}})});
                                        });

            function ajaxAction(action) {
                data = $("#frm_" + action).serializeArray();
                $.ajax({
                    type: "POST",
                    url: "response.php",
                    data: data,
                    dataType: "json",
                    success: function(response) {
                        $('#' + action + '_model').modal('hide');
                        $("#gen_grid").bootgrid('reload');
                    }
                });
            }

            $("#command-add").click(function() {
                $("#sub_id").val(""),
                $("#password").val(btoa(+new Date).substr(-7, 5)),
                $("#gen_url").val(""),
                $("#sm_url").val("<?php echo $team ?>"),
                $('#add_model').modal('show');
            });
            $("#btn_add").click(function() {
                if ($.trim($("#sub_id").val()) === "" || $.trim($("#gen_url").val()) === "") {
                    Swal.fire({
                        allowOutsideClick: false,
                        type: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong! {Required all fields}'
                        })
                    return false;
                    }
                ajaxAction('add');
            });
            $("#btn_edit").click(function() {
                ajaxAction('edit');
            });
        });
        $('#sub_id, #edit_sub_id').bind('keypress', function (event) {
    var regex = new RegExp("^[a-zA-Z0-9]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key)) {
       event.preventDefault();
       return false;
    }
});
    </script>
    </body></html>