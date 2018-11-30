<div class="inner-right-panel">

    <!--breadcrumb wrap-->
    <div class="breadcrumb-wrap">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo base_url("admin/templates") ?>">Template Management</a></li>
            <li class="breadcrumb-item active">Template Details</li>
        </ol>
    </div>
    <!--breadcrumb wrap close-->

    <!--Filter Section -->
    <div class="section">
        <div class="form-item-title clearfix">
            <h3 class="title">Template Details</h3>
        </div>
        <!-- title and form upper action end-->
            <div class="row">
                <div class="col-lg-12 col-sm-12">
                    <label class="admin-label"><br></label>
                    <!-- cropper image preview box start-->
                    <div class="image-view-wrapper img-view200p img-viewbdr-radius4p img-mb">
                        <div class="profile-pic image-view img-view200" style="background-image:url('<?php echo (!empty($template['image'])) ? $template['image'] : DEFAULT_IMAGE ?>');"></div>
                    </div>
                    <!-- //thumb wrapper -->
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label class="admin-label">Select Lighting</label>
                        <div><?php echo $room_type_map[$template['type']] ?></div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label class="admin-label">Select Category</label>
                        <div class="display">
                            <span><?php echo $template['application_title'] ?></span>
                            <span class=""></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label class="admin-label">Select Room Type</label>
                        <div class="display">
                            <span><?php echo $template['room_type'] ?></span>
                            <span class=""></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label for="" class="admin-label">Room Length</label>
                        <span><?php echo $template['room_length'].$template['room_length_unit'] ?></span>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label for="" class="admin-label">Room Breath</label>
                        <span><?php echo $template['room_breath'].$template['room_breath_unit'] ?></span>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label for="" class="admin-label">Room Height</label>
                        <span><?php echo $template['room_height'].$template['room_height_unit'] ?></span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label for="" class="admin-label">Workplane Height</label>
                        <span><?php echo $template['workplane_height'].$template['workplane_height_unit'] ?></span>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label for="" class="admin-label">Room Shape</label>
                        <div class="display">
                            <span class=""><?php echo $room_shape[$template['room_shape']] ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label for="" class="admin-label">Lux Value</label>
                        <span><?php echo $template['lux_value'] ?></span>
                    </div>
                </div>
            </div>
            
    </div>
</div>