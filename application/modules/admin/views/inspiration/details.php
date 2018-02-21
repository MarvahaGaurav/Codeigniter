<div class="inner-right-panel">
    <!--breadcrumb wrap-->
    <div class="breadcrumb-wrap">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo base_url("/admin/technician") ?>">Merchant Management</a></li>
            <li class="breadcrumb-item"><a href="<?php echo base_url("/admin/technician/detail?id=" . $inspiration_data["user_id"]) ?>">Merchant Details</a></li>
            <li class="breadcrumb-item active">Inspiration Details</li>
        </ol>
    </div>
    <?php //echo '<pre>'; print_r($profile); echo '</pre>'; ?>
    <!--Filter Section -->
    <div class="section">
        
        <div class="row">
            <!-- <div class="col-lg-3 col-sm-3 col-xs-3">
               
                <!-- <div class="form-profile-pic-wrapper pull-left">
                    <div class="profile-pic" style="background-image:url('<?php //echo (!empty($profile['image'])) ? IMAGE_PATH . $profile['image'] : DEFAULT_IMAGE ?>');">
                    </div>
                </div> -->
            <!-- </div> --> 
            <div class="col-lg-9 col-sm-9 col-xs-9">
                <div class="row">
                    <div class="user-detail-panel">        
                        <div class="col-lg-6 col-sm-6 col-xs-6">
                            <div class="form-group">
                                <label class="admin-label">Title</label>
                                <div class="input-holder">
                                    <span class="text-detail"><?php echo $inspiration_data['title'] ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-6 col-sm-6 col-xs-6">
                            <div class="form-group">
                                <label class="admin-label">Posted By</label>
                                <div class="input-holder">
                                    <span class="text-detail"><?php echo $inspiration_data['full_name'] ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-lg-6 col-sm-6 col-xs-6">
                            <div class="form-group">
                                <label class="admin-label">Company Name</label>
                                <div class="input-holder">
                                    <span class="text-detail"><?php echo $inspiration_data['company_name'] ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-xs-6">
                            <div class="form-group">
                                <label class="admin-label">Description</label>
                                <div class="input-holder">
                                    <span class="text-detail"><?php echo $inspiration_data['description'] ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-lg-6 col-sm-6 col-xs-6">
                            <div class="form-group">
                                <label class="admin-label">City</label>
                                <div class="input-holder">
                                    <span class="text-detail"><?php echo $inspiration_data['city_name'] ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-xs-6">
                            <div class="form-group">
                                <label class="admin-label">Country</label>
                                <div class="input-holder">
                                    <span class="text-detail"><?php echo $inspiration_data['country_name'] ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-lg-6 col-sm-6 col-xs-6">
                            <div class="form-group">
                                <label class="admin-label">Media</label>
                                <div class="input-holder">
                                <?php foreach ($inspiration_data['media'] as $value) : ?>
                                    <?php if ( $value['media_type'] == CONTENT_TYPE_IMAGE ) {?>
                                    <div class="col-lg-3 col-sm-3 col-xs-3">
                                        <div class="image-view-wrapper img-view200p img-viewbdr-radius4p img-mb">
                                            <div class="profile-pic image-view img-view200" style=""></div>
                                            <div class="form-profile-pic-wrapper pull-left">
                                                <div class="profile-pic" style="background-image:url('<?php echo (!empty($value['media'])) ? IMAGE_PATH . $value : DEFAULT_IMAGE ?>');">
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } else if ( $value['media_type'] == CONTENT_TYPE_VIDEO ) { ?>
                                        <video width="320" height="240" poster="<?php echo $value['video_thumbnail'] ?>" controls>
                                            <source src="<?php echo $value['media'] ?>" type="video/mp4">
                                            <source src="<?php echo $value['media'] ?>" type="video/ogg">
                                            Your browser does not support the video tag.
                                        </video>
                                    <?php } ?>
                                <?php endforeach ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!--Filter Section -->
    </div>
</div>
