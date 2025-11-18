<?php include_once('../login.php');?>
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
                    <li><a href="/admin.panel/"><strong>Dashboard</strong></a></li>
                    <li class="active"><a href="#"><strong>Campaigns</strong></a></li>
                    <li><a href="/admin.domain/"><strong>Addon Domain</strong></a></li>
                </ul>
            </div>
            <!--/.nav-collapse -->
        </div>
    </div>
    <div class="container table-responsive" style="table-layout: auto;">
        <div class="panel panel-default">
            <div class="panel-heading">
                <code></code>
                <div class="pull-right">
                    <button type="button" class="btn btn-xs btn-primary" id="command-add" data-row-id="0">
                        <span class="glyphicon glyphicon-plus"></span> Create Campaigns</button>
                </div>
            </div>
            <div class="panel-body">
                    <table id="gen_grid" class="table table-bordered table-hover table-striped table-condensed" cellspacing="0" data-toggle="bootgrid" style="table-layout: auto;">
                    <thead>
                        <tr>
                            <th data-column-id="id" data-type="numeric" data-identifier="true">Empid</th>
                            <th data-column-id="country_code">Country Code</th>
                            <th data-column-id="ua">User Agent</th>
                            <th data-column-id="offer">Offer</th>
                            <th data-column-id="network">Network</th>
                            <th data-align="right" data-column-id="commands" data-formatter="commands" data-sortable="false">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    </div>
    <div id="add_model" class="modal fade" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog" style="width:750px;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Create Campaigns</h4>
                </div>
                <div class="modal-body">
                <blockquote>
                    <p class="text-warning">Smartlink Parameter:</p>
                    <footer class="text-muted">IMO: https://domain.com/c/xx?s1=xx&s2=xx&s3=<font style="color:#ea4335;">{sub_id}</font>&click_id=<font style="color:#ea4335;">{click_id}</font></footer>
                    <footer class="text-muted">LOS: https://domain.com/?u=xxx&o=xxx&t=<font style="color:#ea4335;">{sub_id}</font>&cid=<font style="color:#ea4335;">{click_id}</font></footer>
                    <footer class="text-muted">TRZ: https://domain.com/click?campaign_id=x&pub_id=x&p1=<font style="color:#ea4335;">{click_id}</font>&source=<font style="color:#ea4335;">{sub_id}</font></footer>
                    </blockquote>  
                <blockquote>
                    <p class="text-warning">Postback URL:</p>
                    <footer class="text-muted">IMO: https://report.<?php echo $_SERVER['HTTP_HOST'] ?>/postback/?click_id=<font style="color:#ea4335;">&lt;click_id&gt;</font>&payout=<font style="color:#ea4335;">&lt;payout&gt;</font></footer>
                    <footer class="text-muted">LOS: https://report.<?php echo $_SERVER['HTTP_HOST'] ?>/postback/?click_id=<font style="color:#ea4335;">{cid}</font>&payout=<font style="color:#ea4335;">{sum}</font></footer>
                    <footer class="text-muted">TRZ: https://report.<?php echo $_SERVER['HTTP_HOST'] ?>/postback/?click_id=<font style="color:#ea4335;">{p1}</font>&payout=<font style="color:#ea4335;">{payout}</font></footer>
                    </blockquote>  
                    <form method="post" id="frm_add">
                        <input type="hidden" value="add" name="action" id="action">
                        <div class="form-group">
                            <!--<label for="salary" class="control-label">Country Code:</label>
                            <select id="c_code" class="form-control" type="button">
                                <option value="" disabled="" selected="selected">* Select Country Code</option>
                                <option value="AF">AF - Afghanistan</option>
                                <option value="AX">AX - Aland Islands</option>
                                <option value="AL">AL - Albania</option>
                                <option value="DZ">DZ - Algeria</option>
                                <option value="AS">AS - American Samoa</option>
                                <option value="AD">AD - Andorra</option>
                                <option value="AO">AO - Angola</option>
                                <option value="AI">AI - Anguilla</option>
                                <option value="AQ">AQ - Antarctica</option>
                                <option value="AG">AG - Antigua and Barbuda</option>
                                <option value="AR">AR - Argentina</option>
                                <option value="AM">AM - Armenia</option>
                                <option value="AW">AW - Aruba</option>
                                <option value="AC">AC - Ascension Island</option>
                                <option value="AU">AU - Australia</option>
                                <option value="AT">AT - Austria</option>
                                <option value="AZ">AZ - Azerbaijan</option>
                                <option value="BS">BS - Bahamas</option>
                                <option value="BH">BH - Bahrain</option>
                                <option value="BB">BB - Barbados</option>
                                <option value="BD">BD - Bangladesh</option>
                                <option value="BY">BY - Belarus</option>
                                <option value="BE">BE - Belgium</option>
                                <option value="BZ">BZ - Belize</option>
                                <option value="BJ">BJ - Benin</option>
                                <option value="BM">BM - Bermuda</option>
                                <option value="BT">BT - Bhutan</option>
                                <option value="BW">BW - Botswana</option>
                                <option value="BO">BO - Bolivia</option>
                                <option value="BA">BA - Bosnia and Herzegovina</option>
                                <option value="BV">BV - Bouvet Island</option>
                                <option value="BR">BR - Brazil</option>
                                <option value="IO">IO - British Indian Ocean Territory</option>
                                <option value="BN">BN - Brunei Darussalam</option>
                                <option value="BG">BG - Bulgaria</option>
                                <option value="BF">BF - Burkina Faso</option>
                                <option value="BI">BI - Burundi</option>
                                <option value="KH">KH - Cambodia</option>
                                <option value="CM">CM - Cameroon</option>
                                <option value="CA">CA - Canada</option>
                                <option value="CV">CV - Cape Verde</option>
                                <option value="KY">KY - Cayman Islands</option>
                                <option value="CF">CF - Central African Republic</option>
                                <option value="TD">TD - Chad</option>
                                <option value="CL">CL - Chile</option>
                                <option value="CN">CN - China</option>
                                <option value="CX">CX - Christmas Island</option>
                                <option value="CC">CC - Cocos (Keeling) Islands</option>
                                <option value="CO">CO - Colombia</option>
                                <option value="KM">KM - Comoros</option>
                                <option value="CG">CG - Congo</option>
                                <option value="CD">CD - Congo, Democratic Republic</option>
                                <option value="CK">CK - Cook Islands</option>
                                <option value="CR">CR - Costa Rica</option>
                                <option value="CI">CI - Cote D'Ivoire (Ivory Coast)</option>
                                <option value="HR">HR - Croatia (Hrvatska)</option>
                                <option value="CU">CU - Cuba</option>
                                <option value="CY">CY - Cyprus</option>
                                <option value="CZ">CZ - Czechia (Czech Republic)</option>
                                <option value="DK">DK - Denmark</option>
                                <option value="DJ">DJ - Djibouti</option>
                                <option value="DM">DM - Dominica</option>
                                <option value="DO">DO - Dominican Republic</option>
                                <option value="TP">TP - East Timor</option>
                                <option value="EC">EC - Ecuador</option>
                                <option value="EG">EG - Egypt</option>
                                <option value="SV">SV - El Salvador</option>
                                <option value="GQ">GQ - Equatorial Guinea</option>
                                <option value="ER">ER - Eritrea</option>
                                <option value="EE">EE - Estonia</option>
                                <option value="ET">ET - Ethiopia</option>
                                <option value="EU">EU - European Union</option>
                                <option value="FK">FK - Falkland Islands (Malvinas)</option>
                                <option value="FO">FO - Faroe Islands</option>
                                <option value="FJ">FJ - Fiji</option>
                                <option value="FI">FI - Finland</option>
                                <option value="FR">FR - France</option>
                                <option value="FX">FX - France, Metropolitan</option>
                                <option value="GF">GF - French Guiana</option>
                                <option value="PF">PF - French Polynesia</option>
                                <option value="TF">TF - French Southern Territories</option>
                                <option value="MK">MK - F.Y.R.O.M. (Macedonia)</option>
                                <option value="GA">GA - Gabon</option>
                                <option value="GM">GM - Gambia</option>
                                <option value="GE">GE - Georgia</option>
                                <option value="DE">DE - Germany</option>
                                <option value="GH">GH - Ghana</option>
                                <option value="GI">GI - Gibraltar</option>
                                <option value="GB">GB - Great Britain (UK)</option>
                                <option value="GR">GR - Greece</option>
                                <option value="GL">GL - Greenland</option>
                                <option value="GD">GD - Grenada</option>
                                <option value="GP">GP - Guadeloupe</option>
                                <option value="GU">GU - Guam</option>
                                <option value="GT">GT - Guatemala</option>
                                <option value="GG">GG - Guernsey</option>
                                <option value="GN">GN - Guinea</option>
                                <option value="GW">GW - Guinea-Bissau</option>
                                <option value="GY">GY - Guyana</option>
                                <option value="HT">HT - Haiti</option>
                                <option value="HM">HM - Heard and McDonald Islands</option>
                                <option value="HN">HN - Honduras</option>
                                <option value="HK">HK - Hong Kong</option>
                                <option value="HU">HU - Hungary</option>
                                <option value="IS">IS - Iceland</option>
                                <option value="IN">IN - India</option>
                                <option value="ID">ID - Indonesia</option>
                                <option value="IR">IR - Iran</option>
                                <option value="IQ">IQ - Iraq</option>
                                <option value="IE">IE - Ireland</option>
                                <option value="IL">IL - Israel</option>
                                <option value="IM">IM - Isle of Man</option>
                                <option value="IT">IT - Italy</option>
                                <option value="JE">JE - Jersey</option>
                                <option value="JM">JM - Jamaica</option>
                                <option value="JP">JP - Japan</option>
                                <option value="JO">JO - Jordan</option>
                                <option value="KZ">KZ - Kazakhstan</option>
                                <option value="KE">KE - Kenya</option>
                                <option value="KI">KI - Kiribati</option>
                                <option value="KP">KP - Korea (North)</option>
                                <option value="KR">KR - Korea (South)</option>
                                <option value="XK">XK - Kosovo*</option>
                                <option value="KW">KW - Kuwait</option>
                                <option value="KG">KG - Kyrgyzstan</option>
                                <option value="LA">LA - Laos</option>
                                <option value="LV">LV - Latvia</option>
                                <option value="LB">LB - Lebanon</option>
                                <option value="LI">LI - Liechtenstein</option>
                                <option value="LR">LR - Liberia</option>
                                <option value="LY">LY - Libya</option>
                                <option value="LS">LS - Lesotho</option>
                                <option value="LT">LT - Lithuania</option>
                                <option value="LU">LU - Luxembourg</option>
                                <option value="MO">MO - Macau</option>
                                <option value="MG">MG - Madagascar</option>
                                <option value="MW">MW - Malawi</option>
                                <option value="MY">MY - Malaysia</option>
                                <option value="MV">MV - Maldives</option>
                                <option value="ML">ML - Mali</option>
                                <option value="MT">MT - Malta</option>
                                <option value="MH">MH - Marshall Islands</option>
                                <option value="MQ">MQ - Martinique</option>
                                <option value="MR">MR - Mauritania</option>
                                <option value="MU">MU - Mauritius</option>
                                <option value="YT">YT - Mayotte</option>
                                <option value="MX">MX - Mexico</option>
                                <option value="FM">FM - Micronesia</option>
                                <option value="MC">MC - Monaco</option>
                                <option value="MD">MD - Moldova</option>
                                <option value="MN">MN - Mongolia</option>
                                <option value="ME">ME - Montenegro</option>
                                <option value="MS">MS - Montserrat</option>
                                <option value="MA">MA - Morocco</option>
                                <option value="MZ">MZ - Mozambique</option>
                                <option value="MM">MM - Myanmar</option>
                                <option value="NA">NA - Namibia</option>
                                <option value="NR">NR - Nauru</option>
                                <option value="NP">NP - Nepal</option>
                                <option value="NL">NL - Netherlands</option>
                                <option value="AN">AN - Netherlands Antilles</option>
                                <option value="NT">NT - Neutral Zone</option>
                                <option value="NC">NC - New Caledonia</option>
                                <option value="NZ">NZ - New Zealand (Aotearoa)</option>
                                <option value="NI">NI - Nicaragua</option>
                                <option value="NE">NE - Niger</option>
                                <option value="NG">NG - Nigeria</option>
                                <option value="NU">NU - Niue</option>
                                <option value="NF">NF - Norfolk Island</option>
                                <option value="MP">MP - Northern Mariana Islands</option>
                                <option value="NO">NO - Norway</option>
                                <option value="OM">OM - Oman</option>
                                <option value="PK">PK - Pakistan</option>
                                <option value="PW">PW - Palau</option>
                                <option value="PS">PS - Palestinian Territory, Occupied</option>
                                <option value="PA">PA - Panama</option>
                                <option value="PG">PG - Papua New Guinea</option>
                                <option value="PY">PY - Paraguay</option>
                                <option value="PE">PE - Peru</option>
                                <option value="PH">PH - Philippines</option>
                                <option value="PN">PN - Pitcairn</option>
                                <option value="PL">PL - Poland</option>
                                <option value="PT">PT - Portugal</option>
                                <option value="PR">PR - Puerto Rico</option>
                                <option value="QA">QA - Qatar</option>
                                <option value="RE">RE - Reunion</option>
                                <option value="RO">RO - Romania</option>
                                <option value="RU">RU - Russian Federation</option>
                                <option value="RW">RW - Rwanda</option>
                                <option value="GS">GS - S. Georgia and S. Sandwich Isls.</option>
                                <option value="SH">SH - Saint Helena</option>
                                <option value="KN">KN - Saint Kitts and Nevis</option>
                                <option value="LC">LC - Saint Lucia</option>
                                <option value="MF">MF - Saint Martin</option>
                                <option value="VC">VC - Saint Vincent & the Grenadines</option>
                                <option value="WS">WS - Samoa</option>
                                <option value="SM">SM - San Marino</option>
                                <option value="ST">ST - Sao Tome and Principe</option>
                                <option value="SA">SA - Saudi Arabia</option>
                                <option value="SN">SN - Senegal</option>
                                <option value="RS">RS - Serbia</option>
                                <option value="YU">YU - Serbia and Montenegro (former)</option>
                                <option value="SC">SC - Seychelles</option>
                                <option value="SL">SL - Sierra Leone</option>
                                <option value="SG">SG - Singapore</option>
                                <option value="SI">SI - Slovenia</option>
                                <option value="SK">SK - Slovakia</option>
                                <option value="SB">SB - Solomon Islands</option>
                                <option value="SO">SO - Somalia</option>
                                <option value="ZA">ZA - South Africa</option>
                                <option value="SS">SS - South Sudan</option>
                                <option value="ES">ES - Spain</option>
                                <option value="LK">LK - Sri Lanka</option>
                                <option value="SD">SD - Sudan</option>
                                <option value="SR">SR - Suriname</option>
                                <option value="SJ">SJ - Svalbard & Jan Mayen Islands</option>
                                <option value="SZ">SZ - Swaziland</option>
                                <option value="SE">SE - Sweden</option>
                                <option value="CH">CH - Switzerland</option>
                                <option value="SY">SY - Syria</option>
                                <option value="TW">TW - Taiwan</option>
                                <option value="TJ">TJ - Tajikistan</option>
                                <option value="TZ">TZ - Tanzania</option>
                                <option value="TH">TH - Thailand</option>
                                <option value="TG">TG - Togo</option>
                                <option value="TK">TK - Tokelau</option>
                                <option value="TO">TO - Tonga</option>
                                <option value="TT">TT - Trinidad and Tobago</option>
                                <option value="TN">TN - Tunisia</option>
                                <option value="TR">TR - Turkey</option>
                                <option value="TM">TM - Turkmenistan</option>
                                <option value="TC">TC - Turks and Caicos Islands</option>
                                <option value="TV">TV - Tuvalu</option>
                                <option value="UG">UG - Uganda</option>
                                <option value="UA">UA - Ukraine</option>
                                <option value="AE">AE - United Arab Emirates</option>
                                <option value="UK">UK - United Kingdom</option>
                                <option value="GB">GB - United Kingdom (Great Britain)</option>
                                <option value="US">US - United States</option>
                                <option value="UM">UM - US Minor Outlying Islands</option>
                                <option value="UY">UY - Uruguay</option>
                                <option value="SU">SU - USSR (former)</option>
                                <option value="UZ">UZ - Uzbekistan</option>
                                <option value="VU">VU - Vanuatu</option>
                                <option value="VA">VA - Vatican City State (Holy See)</option>
                                <option value="VE">VE - Venezuela</option>
                                <option value="VN">VN - Viet Nam</option>
                                <option value="VG">VG - British Virgin Islands</option>
                                <option value="VI">VI - Virgin Islands (U.S.)</option>
                                <option value="WF">WF - Wallis and Futuna Islands</option>
                                <option value="EH">EH - Western Sahara</option>
                                <option value="YE">YE - Yemen</option>
                                <option value="ZM">ZM - Zambia</option>
                                <option value="ZR">(ZR - Zaire) - See CD Congo, Democratic Republic</option>
                                <option value="ZW">ZW - Zimbabwe</option>
                            </select>
                            <hr style="margin:1px;padding:1px;border:0;border-bottom:0px">-->
                            <input readonly="readonly" type="hidden" class="form-control" id="country_code" name="country_code" required="true" />
                        </div>
                            <div class="form-group">
                            <!--<label for="salary" class="control-label">User Agent:</label>
                            <select id="c_ua" class="form-control" type="button">
                                <option value="" disabled="" selected="selected">* Select User Agent</option>
                                <option value="WAP">WAP {MOBILE}</option>
                                <option value="WEB">WEB {DEKSTOP}</option>
                            </select>
                            <hr style="margin:1px;padding:1px;border:0;border-bottom:0px">-->
                            <input readonly="readonly" type="hidden" class="form-control" id="ua" name="ua" required="true" />
                        </div>
                         <div class="form-group">
                            <label for="name" class="control-label">Offer:</label>
                            <input type="text" class="form-control" id="offer" name="offer" placeholder="https://domain.com/..." required/>
                        </div>
                            <div class="form-group">
                            <label for="salary" class="control-label">Select Network:</label>
                            <select id="c_net" class="form-control" type="button">
                                <option value="" disabled="" selected="selected">* Select Network</option>
                                <option value="IMONETIZEIT">IMONETIZEIT</option>
                                <option value="LOSPOLLOS">LOSPOLLOS</option>
                                <option value="TORAZZO">TORAZZO</option>
                            </select>
                            <hr style="margin:1px;padding:1px;border:0;border-bottom:0px">
                            <input readonly="readonly" type="hidden" class="form-control" id="network" name="network" required="true" />
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
                    <h4 class="modal-title">Edit Offer</h4>
                </div>
                <div class="modal-body">
                    <form method="post" id="frm_edit">
                        <input type="hidden" value="edit" name="action" id="action">
                        <input type="hidden" value="0" name="edit_id" id="edit_id">
                        <div class="form-group">
                            <input type="hidden" class="form-control" id="edit_country_code" name="edit_country_code" required="true" readonly="readonly"/>
                        </div>
                        <div class="form-group">
                            <input type="hidden" class="form-control" id="edit_ua" name="edit_ua" required="true" readonly="readonly"/>
                        </div>
                        <div class="form-group">
                            <label for="name" class="control-label">Offer:</label>
                            <input type="text" class="form-control" id="edit_offer" name="edit_offer" required/>
                        </div>
                        <div class="form-group">
                            <label for="name" class="control-label">Network:</label>
                            <input type="text" class="form-control" id="edit_network" name="edit_network" readonly required/>
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
            $("#c_net").change(c_net);
            function c_net() {
                $("#network").val(this.value);
                }
            $("#c_code").change(change_code);
            function change_code() {
                $("#country_code").val(this.value);
                }
            $("#c_ua").change(change_ua);
            function change_ua() {
                $("#ua").val(this.value);
                }
            var grid = $("#gen_grid").bootgrid({
                ajax: true,
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
                        $('#edit_country_code').val(ele.siblings(':nth-of-type(2)').html());
                        $('#edit_ua').val(ele.siblings(':nth-of-type(3)').html());
                        $('#edit_offer').val(ele.siblings(':nth-of-type(4)').html());
                        $('#edit_network').val(ele.siblings(':nth-of-type(5)').html());
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
                $("#c_net")[0].selectedIndex = 0;
                //$("#c_code")[0].selectedIndex = 0;
                //$("#c_ua")[0].selectedIndex = 0;
                $("#country_code").val("global"),
                $("#ua").val("global"),
                $("#offer").val(""),
                $("#network").val(""),
                $('#add_model').modal('show');
            });
            $("#btn_add").click(function() {
                if ($.trim($("#network").val()) === "" || $.trim($("#offer").val()) === "") {
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
    </script>
    </body></html>
