<link rel="stylesheet" href="public/css/web/plugins/bootstrap-select.min.css">

<div class="inner-container">
    <div class="container">

        <!-- breadcrumb -->
        <ul class="breadcrumb">
            <li>
                <a href="<?php echo base_url() ?>">Home</a>
            </li>
            <li>
                <a href="<?php echo base_url("/home/profile/" . encryptDecrypt($user['user_id'])) ?>">My Profile</a>
            </li>
            <li class="active">Edit Profile</li>
        </ul>
        <!-- //breadcrumb -->

        <div class="page-heading">
            <h1 class="page-title">Edit Profile</h1>
        </div>

        <!-- Technician Profile Detail -->
        <div class="technician-profile-wrapper">
            <div class="row">
                <?php echo form_open_multipart('', array('id' => 'signupwebform', 'name' => "signupwebform")) ?>
                <div class="col-sm-3 col-xs-12">
                    <div class="profile-thumb">
                        <h3 class="profile-username">Profile Image</h3>
                        <!-- Thumb Wrapper -->
                        <div class="thumb-view-wrapper thumb-view-p5 img-viewbdr-radius3p">
                            <!--<div class="thumb-view thumb-viewfullheight-5" style="background:url('<?php //echo isset($user['image']) && !empty($user['image']) ? $user['image'] : base_url("public/images/missing_avatar.svg") ?>')"></div>-->
                            <img style="width: 100%;height: 100%;" class="profile-pic thumb-view thumb-viewfullheight-5" id="profile_image" src="<?php echo isset($user['image']) && !empty($user['image']) ? $user['image'] : base_url("public/images/missing_avatar.svg") ?>">
                        </div>
                        <!-- //Thumb Wrapper -->
                        <label class="browse-btn" onclick="addCoverImage1()">   
                            <input type="hidden" name="imgurl" class="inputhidden">
                            <input type="hidden" id="previmg" name="previmg" value="<?php echo isset($user['image']) && !empty($user['image']) ? $user['image'] : '' ?>">
                            <span class="custom-btn btn-margin btn-width save">Browse Image</span>
                        </label>
                    </div>

                    <?php if (!empty($compnaydetail) && $user['is_owner'] == 2) { ?>
                        <hr>
                        <div class="profile-thumb">
                            <h3 class="profile-username">Company Logo</h3>
                            <!-- Thumb Wrapper -->
                            <div class="thumb-view-wrapper thumb-view-p5 img-viewbdr-radius3p">
                                <!--<div class="thumb-view thumb-viewfullheight-5" style="background:url('<?php //echo isset($compnaydetail['company_image']) && !empty($compnaydetail['company_image']) ? $compnaydetail['company_image'] : base_url("public/images/missing_avatar.svg") ?>')"></div>-->
                                <img style="width: 100%;height: 100%;" class="profile-pic2 thumb-view thumb-viewfullheight-5" id="profile_image2" src="<?php echo isset($compnaydetail['company_image']) && !empty($compnaydetail['company_image']) ? $compnaydetail['company_image'] : base_url("public/images/missing_avatar.svg") ?>">
                            </div>
                            <!-- //Thumb Wrapper -->
                            <label class="browse-btn" onclick="addCoverImage2()">  
                                <input type="hidden" name="company_image" class="inputhidden2">
                                <input type="hidden" id="prevcompimg" name="prevcompimg" value="<?php echo isset($compnaydetail['company_image']) && !empty($compnaydetail['company_image']) ? $compnaydetail['company_image'] : '' ?>">
                                <span class="custom-btn btn-margin btn-width save">Browse Image</span>
                            </label>
                        </div>
                    <?php } ?>
                </div>
                <div class="col-sm-9 col-xs-12">

                    <!-- User detail Block wise -->
                    <div class="user-detail-block2">
                        <div class="row">
                            <div class="col-xs-12">
                                <h3 class="userblock-caption">Basic Details</h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="p-label">Name</label>
                                    <div class="form-group-field">
                                        <input type="text" maxlength="255" value="<?php echo $user['first_name'] ?>" name="name" placeholder="Company Name">
                                    </div>
                                    <div class="error"><?php echo form_error('name') ?></div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="p-label">Email ID</label>
                                    <div class="form-group-field">
                                        <input type="text" value="<?php echo $user['email'] ?>" readonly="readonly" name="email"  placeholder="Email ID">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- User detail Block wise -->
                    <div class="user-detail-block2">
                        <div class="row">
                            <div class="col-lg-6 col-sm-6 col-xs-12">
                                <label class="p-label">Contact Number</label>
                                <div class="form-group-inline clearfix">
                                    <div class="form-group-field bootstrap-selectpicker">
                                        <span class="pluscode">+</span> 
                                        <!--<input type="text" value="<?php //echo $user['prm_user_countrycode']; ?>" name="prmccode" class="ccode" placeholder="Country Code">-->
                                        <select class="selectpicker select-filed-name2 ccode" name="prmccode" data-live-search="true">
                                            <?php foreach ($countries as $country) : ?>
                                                <option value="<?php echo $country['calling_code'] ?>" <?php echo ($country['calling_code'] == $user['prm_user_countrycode']) ? "selected" : "" ?> ><?php echo '('.$country['country_code1'].') '.$country['name'] ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                    <div class="form-group-field">
                                        <input type="text" value="<?php echo $user['phone']; ?>" maxlength="20" name="phone" class="codephone" placeholder="Contact Number">
                                    </div>
                                    <div class="error"><?php echo form_error('phone') ?></div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6 col-xs-12">
                                <label class="p-label">Alternate Number</label>
                                <div class="form-group-inline clearfix">
                                    <div class="form-group-field bootstrap-selectpicker">
                                        <span class="pluscode">+</span>
                                        <!--<input type="text" value="<?php //echo $user['alt_user_countrycode']; ?>" name="altccode" class="ccode" placeholder="Country Code">-->
                                        <select class="selectpicker select-filed-name2 ccode" name="altccode" data-live-search="true">
                                            <?php foreach ($countries as $country) : ?>
                                                <option value="<?php echo $country['calling_code'] ?>" <?php echo ($country['calling_code'] == $user['alt_user_countrycode']) ? "selected" : "" ?> ><?php echo '('.$country['country_code1'].') '.$country['name'] ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                    <div class="form-group-field">
                                        <input type="text" value="<?php echo $user['alt_userphone']; ?>" maxlength="20" name="alt_phone" class="codephone" placeholder="Alternate Number">
                                    </div>
                                    <div class="error"><?php echo form_error('alt_phone') ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- User detail Block wise end -->

                    <?php if (!empty($compnaydetail) && $user['is_owner'] == 2) { ?>
                        <!-- User detail Block wise -->
                        <div class="user-detail-block2">
                            <div class="row">
                                <div class="col-xs-12">
                                    <h3 class="userblock-caption">Company Details</h3>
                                </div>
                            </div><input type="hidden" name="company_id" value="<?php echo $compnaydetail['company_id'] ?>">
                            <div class="row">
                                <div class="col-lg-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label class="p-label">Company Name</label>
                                        <div class="form-group-field">
                                            <input type="text" name="company_name" maxlength="255" value="<?php echo $compnaydetail['company_name'] ?>" placeholder="Smart Guide Pvt. Ltd.">
                                        </div>
                                        <div class="error"><?php echo form_error('company_name') ?></div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label class="p-label">Company Reg. Number</label>
                                        <div class="form-group-field">
                                            <input type="text" name="company_reg_number" maxlength="30" value="<?php echo $compnaydetail['company_reg_number'] ?>" placeholder="12131321321">
                                        </div>
                                        <div class="error"><?php echo form_error('company_reg_number') ?></div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- User detail Block wise end -->
                    <?php } ?>
                    <!-- User detail Block wise -->
                    <div class="user-detail-block2">
                        <div class="row">
                            <div class="col-xs-12">
                                <h3 class="userblock-caption">Address Details</h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="p-label">Country</label>
                                    <div class="form-group-field">
                                        <select class="selectpicker select-filed-name2 country" name="country" data-live-search="true">
                                            <?php foreach ($countries as $country) : ?>
                                                <option value="<?php echo $country['country_code1'] ?>" <?php echo $country['country_code1'] == $user['country_id'] ? "selected" : "" ?> ><?php echo $country['name'] ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="p-label">City</label>
                                    <div class="form-group-field">
                                        <select class="selectpicker select-filed-name2 cities" name="city" data-live-search="true">
                                            <?php foreach ($cities as $city) : ?>
                                                <option value="<?php echo $city['id'] ?>" <?php echo $city['id'] == $user['city_id'] ? "selected" : "" ?>><?php echo $city['name'] ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- User detail Block wise end -->

                    <!-- User detail Block wise -->
                    <div class="user-detail-block2">
                        <div class="row">
                            <div class="col-lg-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="p-label">Zip Code</label>
                                    <div class="form-group-field">
                                        <input type="text" maxlength="10" name="zip_code" value="<?php echo $user['zipcode'] ?>" placeholder="12131321321">
                                    </div>
                                    <div class="error"><?php echo form_error('zip_code') ?></div>
                                </div>
                            </div>

                            <!-- button wrapper -->
                            <div class="col-xs-12">
                                <div class="button-wrapper">
                                    <input type="submit" id="addshopbtn" value="Save" class="custom-btn btn-margin btn-width save">
                                    <button type="submit" class="custom-btn btn-margin btn-width cancel">Cancel</button>
                                </div>
                            </div>
                            <!-- button wrapper end -->
                        </div>
                    </div>
                    <!-- User detail Block wise -->

                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
        <!-- Technician Profile Detail End -->

    </div>
</div>
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
    function addCoverImage1() {
        callme('coverPicInput', '640', '640', 'imagepicker2', 'addshopbtn', 'imageMe1', 'true', '', 'nofixed');
    }
    function addCoverImage2() {
        callme('coverPicInput', '640', '640', 'imagepicker2', 'addshopbtn', 'imageMe1', 'true', '', 'nofixed',2);
    }

    // Selectpicker
    $('.selectpicker').selectpicker();
</script>
<style>
    /* span.pluscode {
        width: 3%;
        float: left;
        padding: 10px 0px;
        font-weight: bold;
    }
    input.ccode {
        width: 34%;
        float: left;
    }
    select.ccode {
        width: 34% !important;
        float: left;
    }
    input.codephone {
        width: 62%;
        float: left;
        margin-left: 1%;
    } */
</style>