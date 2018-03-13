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
                        <div class="profile-pic image-view img-view200" style="background-image:url('<?php echo (!empty($admindetail['admin_profile_pic'])) ? $admindetail['admin_profile_pic'] : DEFAULT_IMAGE ?>');"></div>
                    </div>
                    <!-- //thumb wrapper -->
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label class="admin-label">Select Room Type</label>
                        <div class="display">

                            <span class=""></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label class="admin-label">Select Lighting</label>
                        <div class="display">

                            <span class=""></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label class="admin-label">Select Category</label>
                        <div class="display">

                            <span class=""></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label for="" class="admin-label">Room Length</label>

                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label for="" class="admin-label">Room Breath</label>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label for="" class="admin-label">Room Height</label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label for="" class="admin-label">Workplane Height</label>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label for="" class="admin-label">Room Shape</label>
                        <div class="display">
                            <span class=""></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label for="" class="admin-label">Lux Value</label>
                        <div class="input-holder">
                        </div>
                    </div>
                </div>
            </div>
            
    </div>
</div>