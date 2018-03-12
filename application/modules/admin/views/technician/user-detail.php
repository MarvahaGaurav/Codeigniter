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
                        <div class="clearfix"></div>
                        <div class="col-lg-6 col-sm-6 col-xs-6">
                            <div class="form-group">
                                <label class="admin-label">User Type</label>
                                <div class="input-holder">
                                    <span class="text-detail"><?php echo $profile['user_type'] ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-xs-6">
                            <div class="form-group">
                                <label class="admin-label">User Role</label>
                                <div class="input-holder">
                                    <span class="text-detail"><?php if($value['user_type_num'] != 1 ){ echo ($value['is_owner'] == '2')?"Owner":"Employee";} ?></span>
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
        
    </div>
    <!--Filter Section -->
    <div class="row">
        <div class="col-lg-12 col-sm-12 col-xs-12">
                <?php
                if ( in_array((int)$profile['user_type_num'], $valid_inspiration_creators) ) { ?>
                <div class="section clearfix">
                    <p class="tt-count">Total Inspirations: <?php echo $total_inspirations ?></p>
                    <div class="table-responsive table-wrapper">
                        <table cellspacing="0" class="table-custom">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Title</th>
                                    <th>Posted By</th>
                                    <th>Company Name</th>
                                    <th>Created Date</th>
                                    <th>Last Updated</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="table_tr">
                                
                            <?php if(isset($inspiration_list) && count($inspiration_list)){
                                    foreach($inspiration_list as $value) : ?>
                                    
                                    <tr id ="remove_<?php echo $value['user_id'];?>" >
                                    <td><?php echo $initial_count; ?></td>
                                    <td><?php echo $value['title']; ?></td>
                                    <td><?php echo $value['full_name']; ?></td>
                                    <td><?php echo $value['company_name']; ?></td>
                                    <td><?php echo $value['created_at']; ?></td>
                                    <td><?php echo $value['updated_at']; ?></td>
                                    <td>
                                        <a class="f-delete" href="<?php echo base_url()?>admin/inspiration/detail?id=<?php echo $value['id'] ?>"><i class="fa fa-eye" title="View Detail" aria-hidden="true"></i></a>
                                        <!-- <a class="f-block" href="javascript:void(0);"  id ="block_<?php echo $value['user_id'];?>" style=""><i class="fa fa-ban" title="block" aria-hidden="true" onclick="blockUser('user',<?php echo BLOCKED;?>,'<?php echo encryptDecrypt($value['user_id']);?>','req/change-user-status','Do you really want to block this user?','Block');"></i></a>
                                        <a class="f-ublock" href="javascript:void(0);" id ="unblock_<?php echo $value['user_id'];?>" style="display:none;"><i class="fa fa-unlock" title="unblock" aria-hidden="true" onclick="blockUser('user',<?php echo ACTIVE;?>,'<?php echo $value['id'] ?>','','Do you really want to unblock this Inspiration?','Unblock');"></i></a>
                                        <a class="f-delete" href="javascript:void(0);"><i class="fa fa-trash" title="Delete" aria-hidden="true" onclick="deleteUser('user',<?php echo DELETED;?>,'<?php echo $value['id'] ?>','','Do you really want to delete this Inspiration?');"></i></a> -->
                                    </td>
                                    <?php $initial_count++; ?>
                                    <?php endforeach ?>
                                </tr>
                                <?php 
                                $i++; 
                                }else { ?>
                                <tr><td colspan="7">No result found.</td></tr> 
                                <?php } ?>
                                </tbody>
                                    </table>
                                </div>
                                <div class="pagination_wrap clearfix">
                                    <?php echo $link;?>
                                </div>
                            </div>  
                            <?php } else { ?>
                                <!-- <tr><td colspan="7">No result found.</td></tr>  -->
                                <?php } ?>
                            
            </div>
        </div>
    <div class="clearfix"></div>
    
</div>
