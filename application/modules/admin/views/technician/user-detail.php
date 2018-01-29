<div class="inner-right-panel">

    <!--breadcrumb wrap-->
    <div class="breadcrumb-wrap">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo base_url() ?>admin/users">Users</a></li>
            <li class="breadcrumb-item active">Technician Detail</li>
        </ol>
    </div>
    <!--Filter Section -->
    <div class="form-item-wrap">
        <div class="form-item-title clearfix">
            <h3 class="title">Technician Detail <button type="button" style="padding:0 20px" class="commn-btn save pull-right"><?php if($profile['user_type'] == 2){ echo ($profile['is_owner'] == '2')?"Owner":"Employee";} ?></button></h3>
        </div>
        <!-- title and form upper action end-->
        <div class="form-ele-wrapper clearfix">
            <div class="row">
                <!--form ele wrapper-->
                <div class="user-detail-panel">
                    <div class="col-lg-3 col-sm-3 col-xs-3">
                        <div class="form-profile-pic-wrapper pull-left">
                            <div class="profile-pic" style="background-image:url('<?php echo (!empty($profile['image'])) ? IMAGE_PATH . $profile['image'] : DEFAULT_IMAGE ?>');">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9 col-sm-9 col-xs-9">
                        <div class="col-lg-6 col-sm-6 col-xs-6">
                            <div class="form-group">
                                <label class="admin-label">Name</label>
                                <div class="input-holder">
                                    <span class="text-detail"><?php echo ucfirst($profile['first_name']) . ' ' . ucfirst($profile['middle_name']) . ' ' . $profile['last_name']; ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-xs-6">
                            <div class="form-group">
                                <label class="admin-label">Email</label>
                                <div class="input-holder">
                                    <span class="text-detail"><?php echo !empty($profile['email'])?$profile['email']:"Not Available"; ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-xs-6">
                            <div class="form-group">
                                <label class="admin-label">Gender</label>
                                <div class="input-holder">
                                    <span class="text-detail"><?php echo ($profile['gender'] == 1) ? 'Male' : ($profile['gender'] == 2) ? 'Female' : 'Other'; ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-xs-6">
                            <div class="form-group">
                                <label class="admin-label">Address</label>
                                <div class="input-holder">
                                    <span class="text-detail"><?php echo $profile['address']; ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-xs-6">
                            <div class="form-group">
                                <label class="admin-label">Phone Number</label>
                                <div class="input-holder">
                                    <span class="text-detail"><?php echo !empty($profile['phone'])?$profile['phone']:"Not Available"; ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-xs-6">
                            <div class="form-group">
                                <label class="admin-label">Register Date</label>
                                <div class="input-holder">
                                    <span class="text-detail"><?php echo date("d M Y H:i a", strtotime($profile['registered_date'])); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
            <!--form ele wrapper end-->
        </div>
        <!--form element wrapper end-->            
    </div>
    <!--Filter Section -->
    
        <!--Filter Section -->
    <div class="form-item-wrap">
        <div class="form-item-title clearfix">
            <h3 class="title">Company Detail</h3>
        </div>
        <?php //echo '<pre>'; print_r($companydetail); echo '</pre>';?>
        <!-- title and form upper action end-->
        <div class="form-ele-wrapper clearfix">
            <div class="row">
                <!--form ele wrapper-->
                <div class="user-detail-panel">
                    <div class="col-lg-3 col-sm-3 col-xs-3">
                        <div class="form-profile-pic-wrapper pull-left">
                            <div class="profile-pic" style="background-image:url('<?php echo (!empty($companydetail['company_image'])) ? IMAGE_PATH . $companydetail['company_image'] :BASE_URL. DEFAULT_IMAGE ?>');">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9 col-sm-9 col-xs-9">
                        <div class="col-lg-6 col-sm-6 col-xs-6">
                            <div class="form-group">
                                <label class="admin-label">Company Name</label>
                                <div class="input-holder">
                                    <span class="text-detail"><?php echo ucfirst($companydetail['company_name']); ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-sm-6 col-xs-6">
                            <div class="form-group">
                                <label class="admin-label">Company Registration Number</label>
                                <div class="input-holder">
                                    <span class="text-detail"><?php echo ucfirst($companydetail['company_reg_number']); ?></span>
                                </div>
                            </div>
                        </div>                       
                        <div class="col-lg-6 col-sm-6 col-xs-6">
                            <div class="form-group">
                                <label class="admin-label">Phone Number</label>
                                <div class="input-holder">
                                    <span class="text-detail"><?php echo !empty($companydetail['prm_contact_number'])?'+'.$companydetail['prm_country_code'].'-'.$companydetail['prm_contact_number']:'NA'; ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-xs-6">
                            <div class="form-group">
                                <label class="admin-label">Alternate Phone Number</label>
                                <div class="input-holder">
                                    <span class="text-detail"><?php echo !empty($companydetail['alt_contact_number'])?'+'.$companydetail['alt_country_code'].'-'.$companydetail['alt_contact_number']:'NA'; ?></span>
                                </div>
                            </div>
                        </div>
                         <div class="col-lg-6 col-sm-6 col-xs-6">
                            <div class="form-group">
                                <label class="admin-label">Address</label>
                                <div class="input-holder">
                                    <span class="text-detail"><?php echo $companydetail['company_address']; ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-xs-6">
                            <div class="form-group">
                                <label class="admin-label">City, State, Country</label>
                                <div class="input-holder">
                                    <span class="text-detail"><?php echo $companydetail['country']; ?>, <?php echo $companydetail['state']; ?>, <?php echo $companydetail['city']; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
            <!--form ele wrapper end-->
        </div>
        <!--form element wrapper end-->            
    </div>
    <!--Filter Section -->
    
</div>
