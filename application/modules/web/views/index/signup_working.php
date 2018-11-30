<body class="form-backgournd">

    <div class="form-bg"></div>
    <!-- ============== Signup Section ============== -->
    <div class="fm-table-section">

        <!-- ============== Right Section ============== -->
        <div class="fm-table-cell tbl-cell-2 skewpart">
            <div class="form-right-cell">
                <div class="skewoverlay"></div>
                <div class="form-logo"><img src="public/images/logo.png" alt="logo"></div>
                <h2>Welcome</h2>
                <p>SG Lighting has vast experience of and expertise in a wide range of different types of projects such as indoor & outdoor lighting.</p>
            </div>
        </div>
        <!-- //Right Cell -->

        <!-- ============== Left Section ============== -->
        <div class="fm-table-cell fm-cell tbl-cell-1">
            <div class="form-wrapper">
                <?php echo form_open_multipart('', array('id' => 'signupwebform', 'name' => "signupwebform")) ?>
                <h1 class="form-caption">Signup</h1>
                <p class="form-desciption">Have an account? <a href="<?php echo base_url(); ?>login" class="create-account">Login Now</a></p>
                <?php echo isset($error) ? '<label class="alert alert-danger">' . $error . '</label>' : form_error('email', '<label class="alert alert-danger">', '</label>') ?>



                <!-- thumb upload -->
                <div class="image-wrapper">
                    <div class="image-view-box img-view130p img-viewbdr-radius">
                        <!--<div id="image-view" class="image-view img-view130" style="background-image:url(public/images/user.png);"></div>-->
                        <img style="width: 100%;height: 100%;" class="profile-pic" id="profile_image" src="<?php echo (!empty($editdata['admin_profile_pic'])) ? $editdata['admin_profile_pic'] : DEFAULT_IMAGE ?>">

                    </div>
                    <!--<div class="upload-btn">
                        <input type="file" name="image" id="upload">
                        <img src="public/images/camera.svg" />
                    </div>-->
                    <div class="image_upload_trigger" onclick="addCoverImage()">
                        <a href="javascript:void(0);" class="upimage-btn"></a>
                        <label class="camera" for="upload"><i class="fa fa-camera" aria-hidden="true"></i></label>
                        <input type="hidden" name="imgurl" class="inputhidden">
                        <input type="hidden" id="imgChange" name="imgChange" value="">
                    </div>
                </div>
                <!-- thumb upload -->

                <!-- Business User -->
                <div class="business">
                    <div class="form-group-inline clearfix">
                        <div class="form-group">
                            <select name="user_type" class="selectpicker" id="user_type" data-live-search="true" data-live-search-style="startsWith" >
                                <option value="">Select User type</option>
                                <option <?php if (set_value('user_type') == '1') {
                    echo 'selected';
                } ?> value="1">Private</option>
                                <option <?php if (set_value('user_type') == '2') {
                    echo 'selected';
                } ?> value="2">Technician</option>
                                <option <?php if (set_value('user_type') == '3') {
                    echo 'selected';
                } ?> value="3">Architect</option>
                                <option <?php if (set_value('user_type') == '4') {
                    echo 'selected';
                } ?> value="4">Electrical Planner</option>
                                <option <?php if (set_value('user_type') == '5') {
                    echo 'selected';
                } ?> value="5">Wholesaler</option>
                                <option <?php if (set_value('user_type') == '6') {
                    echo 'selected';
                } ?> value="6">Business</option>
                            </select>
                            
                        </div>
<?php echo form_error('user_type', '<label class="alert alert-danger">', '</label>') ?>
                    </div>
                    <div class="form-group clearfix hidden" id="confirm_div">
                        <h3 class="account-heading">Are you owner of the company?</h3>
                        <!-- Account Type -->
                        <div class="account-type-wrapper">
                            <!-- <div class="custom-control custom-radio">
                                <input id="currency1" name="currency" value="1" class="custom-control-input" type="radio">
                                <label for="currency1">
                                    <span class="custom-control-indicator"></span>
                                </label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input id="currency1" name="currency" value="1" class="custom-control-input" type="radio">
                                <label for="currency1">
                                    <span class="custom-control-indicator"></span>
                                </label>
                            </div> -->
                            <div class="account-type">
                                <input type="radio" value="2" <?php if (set_value('isowner') == '2') {
    echo 'checked="checked"';
} ?>  name="isowner" class="switch-account">
                                <label for="isowner">
                                    <span></span>
                                    Yes
                                </label>
                            </div>
                            <div class="account-type">
                                <input type="radio" value="1"  <?php if (set_value('isowner') == '1') {
    echo 'checked="checked"';
} ?>  name="isowner" class="switch-account">
                                <label for="isowner">
                                    <span></span>
                                    No
                                </label>

                            </div>
                            <!-- //Account Type -->
                        </div>
                    </div>
                    <div class="form-group-inline clearfix">
                        <div class="form-group">
                            <input value="<?php echo set_value('fullname'); ?>" type="text" class="form-control" maxlength="50" name="fullname" placeholder="Full Name" required="" autofocus="" />
                        </div>
                        <div class="form-group">
                            <input value="<?php echo set_value('email'); ?>"  type="email" class="form-control" maxlength="50" name="email" placeholder="Email Address" required="" autofocus="" />
                        </div>
                        <?php echo form_error('fullname', '<label class="alert alert-danger">', '</label>') ?>
                        <?php echo form_error('email', '<label class="alert alert-danger">', '</label>') ?>
                    </div>

                    <div class="form-group-inline clearfix">
                        <div class="form-group">
                            <input value="<?php echo set_value('password'); ?>"  type="password" class="form-control" maxlength="15" name="password" placeholder="Password" required=""/>
                        </div>
                        <div class="form-group">
                            <input  value="<?php echo set_value('cnfpassword'); ?>" type="password" class="form-control" maxlength="15" name="cnfpassword" placeholder="Confirm Password" required=""/>
                        </div>
                        <?php echo form_error('password', '<label class="alert alert-danger">', '</label>'); ?>
                        <?php echo form_error('cnfpassword', '<label class="alert alert-danger">', '</label>'); ?>

                    </div>
                    
                    <div class="form-group-inline clearfix">
                        <div class="form-group">
                            <span class="pluscode">+</span> 
                            <!--<input value="<?php //echo set_value('prmccode'); ?>"  type="number" class="form-control ccode" name="prmccode" maxlength="4"  placeholder="Country Code"  autofocus="" />-->
                            <select class="selectpicker select-filed-name2 ccode" name="prmccode" required="" data-live-search="true" data-live-search-style="startsWith" >
                                <option value="">Select Country</option>
                                <?php foreach ($countries as $country) : ?>
                                                                    <option  <?php if (set_value('prmccode') == $country['calling_code']) {
                                        echo 'selected';
                                    } ?>  value="<?php echo $country['calling_code'] ?>"  ><?php echo '('.$country['country_code1'].') '.$country['name'] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <input value="<?php echo set_value('phone'); ?>"  type="number" class="form-control codephone" name="phone" maxlength="15"  placeholder="Contact Number"  autofocus="" />
                        </div>
                    </div>

                    <div class="form-group-inline clearfix">
                        <div class="form-group">
                            <span class="pluscode">+</span> 
                            <!--<input value="<?php //echo set_value('altccode'); ?>"  type="number" class="form-control ccode" name="altccode" maxlength="4"  placeholder="Country Code" />-->
                            <select class="selectpicker select-filed-name2 ccode" name="altccode" required="" data-live-search="true" data-live-search-style="startsWith" >
                                <option value="">Select Country</option>
                                <?php foreach ($countries as $country) : ?>
                                    <option  <?php if (set_value('altccode') == $country['calling_code']) {
                                        echo 'selected';
                                    } ?>  value="<?php echo $country['calling_code'] ?>"  ><?php echo '('.$country['country_code1'].') '.$country['name'] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <input value="<?php echo set_value('altphone'); ?>"  type="number" class="form-control codephone" name="altphone" maxlength="15"  placeholder="Alternate Number" />
                        </div>
                        <?php echo form_error('prmccode', '<label class="alert alert-danger">', '</label>'); ?>
                        <?php echo form_error('phone', '<label class="alert alert-danger">', '</label>'); ?>
                        <?php echo form_error('altccode', '<label class="alert alert-danger">', '</label>'); ?>
                        <?php echo form_error('altphone', '<label class="alert alert-danger">', '</label>'); ?>
                    </div>

                    <!-- company Profile -->
                    <div class="company_div hidden" id="company_div">
                        <h3 class="account-heading">Company Details</h3>
                        <div class="form-group-inline clearfix">
                            <div class="form-group">
                                <input value="<?php echo set_value('comp_reg_number'); ?>"  type="number" class="form-control" maxlength="50"  name="comp_reg_number" placeholder="Company Registration Number" />
                            </div>
                            <div class="form-group">
                                <input value="<?php echo set_value('company_name'); ?>"  type="text" class="form-control"  maxlength="150"  name="company_name" placeholder="Company Name"/>
                            </div>
<?php echo form_error('comp_reg_number', '<label class="alert alert-danger">', '</label>'); ?>
<?php echo form_error('company_name', '<label class="alert alert-danger">', '</label>'); ?>
                        </div>

                        <div class="form-group clearfix">
                            <h3 class="account-heading">Company Logo</h3>
                            <!-- thumb upload -->
                            <div class="image-wrapper image-mb">
                                <div class="image-view-box img-view130p img-viewbdr-radius">
                                    <!--<div id="image-view" class="image-view img-view130" style="background-image:url(public/images/user.png);"></div>-->
                                    <img style="width: 100%;height: 100%;" class="profile-pic2" id="profile_image2" src="<?php echo !empty(set_value('company_logo'))?set_value('company_logo'):DEFAULT_IMAGE; ?>">

                                </div>
                                <!--<div class="upload-btn">
                                    <input type="file" name="image" id="upload">
                                    <img src="public/images/camera.svg" />
                                </div>-->
                                <div class="image_upload_trigger" onclick="addCoverImage2()">
                                    <a href="javascript:void(0);" class="upimage-btn">
                                    </a>
                                    <label class="camera" for="upload"><i class="fa fa-camera" aria-hidden="true"></i></label>
                                    <input type="hidden" name="company_logo" class="inputhidden2" value="<?php echo set_value('company_logo'); ?>">
                                </div>
                            </div>
                            <!-- thumb upload -->
                            <!--<div class="chooseFile">
                                
                                <input id="uploadfile" class="form-control" name="company_logo" placeholder="Choose File" disabled="display">
                                <div class="uploadfile-wrap">
                                    <input type="file" id="uploadbtn">
                                    <span>Browse</span>
                                </div>
                            </div>-->
<?php echo form_error('company_logo', '<label class="alert alert-danger">', '</label>'); ?>
                        </div>
                    </div>
                    <!-- company Profile end -->

                    <!-- employee company -->
                    <div class="company_id_div hidden" id="company_id_div">
                        <h3 class="account-heading">Your Company</h3>
                        <div class="form-group">
                            <select name="company_id" class="selectpicker" id="company_id" data-live-search="true" data-live-search-style="startsWith" >
                                <option value="">Select Company</option>   
                                <?php if($companies){ ?>
                                    <?php foreach($companies as $comp){ ?>
                                        <option <?php if (set_value('company_id') == $comp['company_id']){ echo 'selected';} ?> value="<?php echo $comp['company_id']; ?>"><?php echo $comp['company_name']; ?></option>  
                                    <?php } ?>
                                <?php } ?>
                            </select>                            
                        </div>
<?php echo form_error('company_id', '<label class="alert alert-danger">', '</label>'); ?>
                    </div>
                    <!-- employee company end -->



                    <div class="form-group-inline clearfix">
                        <div class="form-group">
                            <select class="selectpicker select-filed-name2 country" name="country" required="" data-live-search="true" data-live-search-style="startsWith" >
                                <option value="">Select Country</option>
<?php foreach ($countries as $country) : ?>
                                    <option  <?php if (set_value('country') == $country['country_code1']) {
        echo 'selected';
    } ?>  value="<?php echo $country['country_code1'] ?>" <?php echo $country['country_code1'] == $user['country_id'] ? "selected" : "" ?> ><?php echo $country['name'] ?></option>
<?php endforeach ?>
                            </select>
                            <span class="customArrow"></span>
                        </div>
                        <div class="form-group">
                            <?php if(!empty(set_value('cities'))){?>
                                <select class="cities selectpicker"  id="citiesselbox" name="cities" data-live-search="true" required="">
                                    <option value="">Select City</option>
                                    <?php if($allcities){
                                        foreach($allcities as $cty){?>
                                        <option <?php if($cty['id'] == set_value('cities')){ echo 'selected';} ?> value="<?php echo $cty['id'] ?>"><?php echo $cty['name']; ?></option>
                                    <?php }} ?>
                                </select>
                            <?php }else{ ?>
                                <select class="cities selectpicker" id="citiesselbox" name="cities" data-live-search="true" required="">
                                    <option value="">Select City</option>
                                </select>
                            <?php } ?>
                            
                            <span class="sg-loader hidden"><img style="height:40px;" src="/public/images/preloader.gif" /></span>
                        </div>
<?php echo form_error('country', '<label class="alert alert-danger">', '</label>'); ?>
<?php echo form_error('cities', '<label class="alert alert-danger">', '</label>'); ?>
                    </div>

                    <div class="form-group-inline clearfix">
                        <div class="form-group no-margin">
                            <input value="<?php echo set_value('zipcode'); ?>"  type="number" class="form-control" maxlength="7" name="zipcode" placeholder="Zipcode" required="" autofocus="" />
                        </div>
<?php echo form_error('zipcode', '<label class="alert alert-danger">', '</label>'); ?>
                    </div>
                </div>

                <div class="form-group form-btn-wrap">
                    <button class="form-btn save" id="submit-signup" type="submit">Signup</button>
                </div>
<?php echo form_close(); ?>
            </div>
        </div>
        <!-- //Left Cell -->

    </div>
    
<script>
        $("#signupwebform").on("submit", function(){
            $("#submit-signup").prop("disabled", true);
        });
        function optionsViewBuilder(data, defaultText) {            
            var html = data.reduce(function (accumulator, currentValue) {
                return accumulator + '<option value="' + currentValue.id + '">' + currentValue.text + "</option>";
            }, '<option value="">' + defaultText + '</option>');

            return html;
        }

        function fetchLocation(url, parent, source, target, events) {
            
            var parent = parent || "body";
            var source = source || ".country";
            var target = target || ".cities";
            var events = events || "change";

            $(parent).on(events, source, function () {
                
                var $self = $(this),
                        selfValue = $self.val();
                $.ajax({
                    url: '<?php echo base_url(); ?>xhttp/cities',
                    method: "GET",                       
                    data: {
                        param: selfValue
                    },
                    dataType: "json",
                    beforeSend:function(){
                        $('span.sg-loader').removeClass('hidden');
                    },
                    success: function (response) {

                        if (response.success) {
                            var data = response.data
                            data = data.map(function (row) {
                                return {id: row.id, text: row.name};
                            });
                            //$(target).html(optionsViewBuilder(data, "Select a city"));
                            $('#citiesselbox').html(optionsViewBuilder(data, "Select a city"));
                            $('.selectpicker').selectpicker('refresh');
                            $('span.sg-loader').addClass('hidden');
                        }
                    },
                    error: function () {

                    }
                });
            });
        }
        fetchLocation();
        $(document).ready(function () {

            //show pop up on from select options
            $('select#user_type').change(function () {
                if ($(this).val() == "2" || $(this).val() == "3" || $(this).val() == "4" || $(this).val() == "5" || $(this).val() == "6") {
                    $('#confirm_div').removeClass('hidden');
                    $('#company_div').removeClass('hidden');
                } else {
                    $('#confirm_div').addClass('hidden');
                    $('#company_div').addClass('hidden');
                }
            });

            $('input[type="radio"].switch-account').change(function () {
                if ($(this).val() == '2') {
                    $('#company_div').removeClass('hidden');
                    $('#company_id_div').addClass('hidden');
                } else if ($(this).val() == '1') {
                    $('#company_div').addClass('hidden');
                    $('#company_id_div').removeClass('hidden');
                }
            });

            <?php if (!empty(set_value('user_type'))) { ?> 
                var usertype = '<?php echo set_value('user_type')?>';
                if (usertype == "2" || usertype == "3" || usertype == "4" || usertype == "5" || usertype == "6") {
                    $('#confirm_div').removeClass('hidden');
                    $('#company_div').removeClass('hidden');
                } else {
                    $('#confirm_div').addClass('hidden');
                    $('#company_div').addClass('hidden');
                }
            <?php } ?>
            <?php if (set_value('isowner') == '2') { ?>
                $('#company_div').removeClass('hidden');
                $('#company_id_div').addClass('hidden');
            <?php } ?>
                
            <?php if (set_value('isowner') == '1') { ?>
                $('#company_div').addClass('hidden');
                $('#company_id_div').removeClass('hidden');
            <?php } ?>                            
        })

        
    </script>
    <!--cropper libraries-->
    <link href="public/cropper/cropper.min.css" rel="stylesheet">
    <script>
        if (location.hostname == "localhost") {
            var domain = location.protocol + '//' + location.hostname + (location.port ? ':' + location.port : '') + '/admin';
            var domain2 = location.protocol + '//' + location.hostname + (location.port ? ':' + location.port : '') + '';
        } else {
            var domain = location.protocol + '//' + location.hostname + (location.port ? ':' + location.port : '') + '/admin';
            var domain2 = location.protocol + '//' + location.hostname + (location.port ? ':' + location.port : '');
        }

    </script>
    <script src="public/cropper/cropper.js"></script>
    <script src="public/cropper/cropper.min.js"></script>
    <script src="public/cropper/main.js"></script>
    <script src="public/js/web/plugins/bootstrap-select.js"></script>
    <script>
        function addCoverImage() {
            callme('coverPicInput', '640', '640', 'imagepicker2', 'addshopbtn', 'imageMe1', 'true', '', 'circular');
        }
        function addCoverImage2() {
            callme('coverPicInput', '640', '640', 'imagepicker2', 'addshopbtn', 'imageMe1', 'true', '', 'nofixed',2);
        }

        // Selectpicker
        $('.selectpicker').selectpicker(refresh);
    </script>

    <style>
        .myloader{
            width: 16%;
            position: absolute;
            margin-top: -29px;
            /*display: none;*/
        }
        .camera {
            position: absolute;
            bottom: 0;
            right: 0;
            font-size: 14px;
            background: #e4001c;
            color: #fff;
            padding: 3px 5px 2px;
            border-top-left-radius: 3px;
            border-bottom-left-radius: 3px;
            cursor: pointer;
        }
        .fa-camera:before {
            content: "\f030";
        }
        .image_upload_trigger {
            position: absolute;
            right: 0;
            bottom:0;  
            width: 38px;
        }    
        label.alert-danger {
            padding: 5px 10px;
            margin: 3px 10px;
            border-radius: 5px;
            clear: both;
            float: left;
        }
        span.sg-loader {
            float: right;
            margin: -40px;
        }
    </style>
    <script src="<?php echo base_url("public/js/ajax-bootstrap-select-min") ?>"></script>
    <script>
        $(".selectpicker").selectpicker().filter('#citiesselbox').ajaxSelectPicker(options);
    </script>
</body>