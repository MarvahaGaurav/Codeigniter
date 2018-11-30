<div class="inner-right-panel">

    <!--breadcrumb wrap-->
    <div class="breadcrumb-wrap">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo base_url() ?>admin/users">Users Management</a></li>
            <li class="breadcrumb-item active">User Detail</li>
        </ol>
    </div>
    <?php //echo '<pre>'; print_r($profile); echo '</pre>'; ?>
    <!--Filter Section -->
    <div class="section">
        <div class="row">
            <div class="user-detail-panel">
                <div class="col-lg-3 col-sm-5 col-xs-12">
                    <!-- Thumb Wrapper -->
                    <div class="image-view-wrapper img-view200p img-viewbdr-radius4p img-mb">
                        <div class="profile-pic image-view img-view200" style="background-image:url('<?php echo (!empty($profile['image'])) ? $profile['image'] : DEFAULT_IMAGE ?>');"></div>
                    </div>
                    <!-- Thumb Wrapper -->
                    <!-- <div class="form-profile-pic-wrapper pull-left">
                        <div class="profile-pic" style="background-image:url('<?php echo (!empty($profile['image'])) ? $profile['image'] : DEFAULT_IMAGE ?>');">
                        </div>
                    </div> -->
                </div>
                <div class="col-lg-9 col-sm-7 col-xs-12">
                    <div class="row">        
                        <div class="col-lg-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label class="admin-label">Name</label>
                                <div class="input-holder">
                                    <span class="text-detail"><?php echo ucfirst($profile['first_name']) . ' ' . ucfirst($profile['middle_name']) . ' ' . $profile['last_name']; ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label class="admin-label">Email</label>
                                <div class="input-holder">
                                    <span class="text-detail"><?php echo !empty($profile['email'])?$profile['email']:"Not Available"; ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-lg-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label class="admin-label">Country</label>
                                <div class="input-holder">
                                    <span class="text-detail"><?php echo !empty($profile['name'])?$profile['name']:"Not Available"; ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-lg-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label class="admin-label">Phone Number</label>
                                <div class="input-holder">
                                    <span class="text-detail"><?php echo !empty($profile['phone'])?$profile['phone']:"Not Available"; ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label class="admin-label">Register Date</label>
                                <div class="input-holder">
                                    <span class="text-detail"><?php echo date("d M Y H:i a", strtotime($profile['registered_date'])); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label class="admin-label">User Type</label>
                                <div class="input-holder">
                                    <span class="text-detail"><?php echo $profile['user_type'] ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php if (BUSINESS_USER === (int)$profile['user_type_numeric'] ) { ?>
        <div class="form-item-title clearfix">
            <h3 class="title">Company Detail</h3>
        </div>
        <?php //echo '<pre>'; print_r($profile); echo '</pre>'; ?>
        <div class="row">
            <!--form ele wrapper-->
            <div class="user-detail-panel">
                <div class="col-lg-3 col-sm-3 col-xs-3">
                    <div class="image-view-wrapper img-view200p img-viewbdr-radius4p img-mb">
                        <img style="width:100%;" src="<?php echo (!empty($profile['company_image'])) ? $profile['company_image'] : DEFAULT_IMAGE ?>" title="Compamy Logo">                         
                    </div>
                    <!--<div class="form-profile-pic-wrapper pull-left">
                        <div class="profile-pic" style="background-image:url('<?php echo (!empty($profile['company_image'])) ? $profile['company_image'] : DEFAULT_IMAGE ?>');">
                        </div>
                    </div>-->
                </div>
                <div class="col-lg-9 col-sm-9 col-xs-9">
                    <div class="row">
                        <div class="col-lg-6 col-sm-6 col-xs-6">
                            <div class="form-group">
                                <label class="admin-label">Company Name</label>
                                <div class="input-holder">
                                    <span class="text-detail"><?php echo ucfirst($profile['company_name']); ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-sm-6 col-xs-6">
                            <div class="form-group">
                                <label class="admin-label">Company Registration Number</label>
                                <div class="input-holder">
                                    <span class="text-detail"><?php echo ucfirst($profile['company_reg_number']); ?></span>
                                </div>
                            </div>
                        </div>                       
                        <div class="col-lg-6 col-sm-6 col-xs-6">
                            <div class="form-group">
                                <label class="admin-label">Phone Number</label>
                                <div class="input-holder">
                                    <span class="text-detail"><?php echo !empty($profile['prm_contact_number'])?'+'.$profile['prm_country_code'].'-'.$profile['prm_contact_number']:'Not Available'; ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-xs-6">
                            <div class="form-group">
                                <label class="admin-label">Alternate Phone Number</label>
                                <div class="input-holder">
                                    <span class="text-detail"><?php echo !empty($profile['alt_contact_number'])?'+'.$profile['alt_country_code'].'-'.$profile['alt_contact_number']:'Not Available'; ?></span>
                                </div>
                            </div>
                        </div>
                        <!--<div class="col-lg-6 col-sm-6 col-xs-6">
                            <div class="form-group">
                                <label class="admin-label">Address</label>
                                <div class="input-holder">
                                    <span class="text-detail"><?php //echo !empty($profile['company_address'])?$profile['company_address']:"Not Available"; ?></span>
                                </div>
                            </div>
                        </div>-->
                        <div class="col-lg-6 col-sm-6 col-xs-6">
                            <div class="form-group">
                                <label class="admin-label">Address</label>
                                <div class="input-holder">
                                    <span class="text-detail"><?php echo $profile['cityname']; ?>, <?php echo $profile['name']; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>  
        <?php }?> 
    </div>            
    <!--Filter Section -->
    
</div>
