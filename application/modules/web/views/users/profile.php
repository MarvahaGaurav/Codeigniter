<div class="inner-container">
    <div class="container">

        <!-- breadcrumb -->
        <ul class="breadcrumb">
            <li>
                <a href="javascript:void(0)">Home</a>
            </li>
            <li class="active">My Profile</li>
        </ul>
        <!-- //breadcrumb -->

        <div class="page-heading">
            <h1 class="page-title">My Profile</h1>
        </div>
         <?php //echo '<pre>'; print_r($user); echo '</pre>'; ?>
         <?php //echo '<pre>'; print_r($compnaydetail); echo '</pre>'; ?>
        <!-- Technician Profile Detail -->
        <div class="technician-profile-wrapper">
            <div class="row">
                <div class="col-sm-3 col-xs-12">
                    <div class="profile-thumb">
                        <!-- Thumb Wrapper -->
                        <div class="thumb-view-wrapper thumb-view-p5 img-viewbdr-radius3p">
                            <div class="thumb-view thumb-viewfullheight-5" style="background:url('<?php echo isset($user['image'])&&!empty($user['image'])?$user['image']:base_url("public/images/missing_avatar.svg") ?>')"></div>
                        </div>
                        <!-- //Thumb Wrapper -->
                        <h3 class="profile-username"><?php echo $user['first_name'] ?></h3>
                        <p></p>
                    </div>
                    <?php if(!empty($compnaydetail)){?>
                    <hr>
                    <div class="profile-thumb">
                        
                        <!-- Thumb Wrapper -->
                        <div class="thumb-view-wrapper thumb-view-p5 img-viewbdr-radius3p">
                            <div class="thumb-view thumb-viewfullheight-5" style="background:url('<?php echo isset($compnaydetail['company_image'])&&!empty($compnaydetail['company_image'])?$compnaydetail['company_image']:base_url("public/images/missing_avatar.svg") ?>')"></div>
                        </div>
                        <!-- //Thumb Wrapper -->
                        <h3 class="profile-username">Company Logo</h3>
                        <p></p>
                    </div>
                    <?php } ?>
                </div>
                <div class="col-sm-9 col-xs-12">

                    <!-- User detail Block wise -->
                    <div class="user-detail-block">
                        <div class="row">
                            <div class="col-xs-12">
                                <h3 class="userblock-caption">Basic Details</h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label class="p-label">Email ID</label>
                                    <div class="input-holder">
                                        <span class="p-label-value"><?php echo $user['email'] ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label class="p-label">Mobile Number</label>
                                    <div class="input-holder">
                                        <span class="p-label-value"><?php echo "+ {$user['prm_user_countrycode']} {$user['phone']}" ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label class="p-label">Alternate Number</label>
                                    <div class="input-holder">
                                        <span class="p-label-value"><?php echo "+ {$user['alt_user_countrycode']} {$user['alt_userphone']}" ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- User detail Block wise end -->

                    <?php if(!empty($compnaydetail)){?>
                    <!-- User detail Block wise -->
                    <div class="user-detail-block">
                        <div class="row">
                            <div class="col-xs-12">
                                <h3 class="userblock-caption">Company Details</h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label class="p-label">Company Name</label>
                                    <div class="input-holder">
                                        <span class="p-label-value"><?php echo $compnaydetail['company_name'] ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label class="p-label">Company Reg. Number</label>
                                    <div class="input-holder">
                                        <span class="p-label-value"><?php echo $compnaydetail['company_reg_number'] ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- User detail Block wise -->
                    <?php }  ?>
                    <!-- User detail Block wise -->
                    <div class="user-detail-block">
                        <div class="row">
                            <div class="col-xs-12">
                                <h3 class="userblock-caption">Address Details</h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label class="p-label">City</label>
                                    <div class="input-holder">
                                        <span class="p-label-value"><?php echo $user['city_name'] ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label class="p-label">Country</label>
                                    <div class="input-holder">
                                        <span class="p-label-value"><?php echo $user['country_name'] ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label class="p-label">Zipcode</label>
                                    <div class="input-holder">
                                        <span class="p-label-value"><?php echo $user['zipcode'] ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- User detail Block wise -->

                    <!-- button wrapper section -->
                    <div class="button-wrapper">
                        <a href="<?php echo base_url("home/profile/{$user_id}/edit") ?>" class="custom-btn btn-margin btn-width save">Edit Profile</a>
                    </div>
                </div>

            </div>
        </div>
        <!-- Technician Profile Detail End -->

    </div>
</div>