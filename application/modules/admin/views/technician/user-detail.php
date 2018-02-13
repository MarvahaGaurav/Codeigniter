<div class="inner-right-panel">

    <!--breadcrumb wrap-->
    <div class="breadcrumb-wrap">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo base_url() ?>admin/technician">Merchant Management</a></li>
            <li class="breadcrumb-item active">Merchant Detail</li>
        </ol>
    </div>
    <?php //echo '<pre>'; print_r($profile); echo '</pre>'; ?>
    <!--Filter Section -->
    <div class="section">
        
        <div class="row">
            <div class="col-lg-3 col-sm-3 col-xs-3">
                <div class="image-view-wrapper img-view200p img-viewbdr-radius4p img-mb">
                    <div class="profile-pic image-view img-view200" style="background-image:url('<?php echo (!empty($profile['image'])) ?$profile['image'] : DEFAULT_IMAGE ?>');"></div>
                </div>
                <!-- <div class="form-profile-pic-wrapper pull-left">
                    <div class="profile-pic" style="background-image:url('<?php //echo (!empty($profile['image'])) ? IMAGE_PATH . $profile['image'] : DEFAULT_IMAGE ?>');">
                    </div>
                </div> -->
            </div>
            <div class="col-lg-9 col-sm-9 col-xs-9">
                <div class="row">
                    <div class="user-detail-panel">        
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
                        <div class="clearfix"></div>
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
                                <label class="admin-label">Country</label>
                                <div class="input-holder">
                                    <span class="text-detail"><?php echo !empty($profile['name'])?$profile['name']:"Not Available"; ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
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
                        <div class="col-lg-6 col-sm-6 col-xs-6">
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

        <div class="form-item-title clearfix">
            <h3 class="title">Company Detail</h3>
        </div>
        <?php //echo '<pre>'; print_r($profile); echo '</pre>';?>
        
        <div class="row">
            <!--form ele wrapper-->
            <div class="user-detail-panel">
                <div class="col-lg-3 col-sm-3 col-xs-3">

                    <div class="image-view-wrapper img-view200p img-viewbdr-radius4p img-mb">
                        <img src="public/images/logo.png" title="Admin Logo">                         
                    </div>
                    <!-- <div class="form-profile-pic-wrapper pull-left">
                        <div class="profile-pic" style="background-image:url('<?php echo (!empty($profile['company_image'])) ? $companydetail['company_image'] :BASE_URL. DEFAULT_IMAGE ?>');">
                        </div>
                    </div> -->
                </div>
                <div class="col-lg-9 col-sm-9 col-xs-9">
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
            <?php
            if ( TECHNICIAN === (int)$profile['user_type_num'] ) { ?>
            <div class="section">
                <p class="tt-count">Total Technicians: <?php echo $totalrows ?></p>
                <div class="table-responsive table-wrapper">
                    <table cellspacing="0" class="table-custom">
                        <thead>
                            <tr>
                                <th>S.No</th>
                            </tr>
                        </thead>
                        <tbody id="table_tr">
                            
                        <?php if(isset($inspiration_list) && count($inspiration_list)){
                                foreach($inspiration_list as $value){ 
                                //echo '<pre>'; print_r($value); echo '</pre>';
                                ?>
                                
                                <tr id ="remove_<?php echo $value['user_id'];?>" >
                                <td><?php echo $i; ?></td>
                                <?php } ?>
                            </tr>
                            <?php 
                            $i++; 
                            } } else { ?>
                            <tr><td colspan="12">No result found.</td></tr
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="pagination_wrap clearfix">
                    <?php echo $link;?>
                </div>
            </div>
          <?php   }
            ?>
        </div>         
    </div>
    <!--Filter Section -->
    
</div>
