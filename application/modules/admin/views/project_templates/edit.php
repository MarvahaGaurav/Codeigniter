<div class="inner-right-panel">

    <!--breadcrumb wrap-->
    <div class="breadcrumb-wrap">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo base_url("admin/templates") ?>">Template Management</a></li>
            <li class="breadcrumb-item active">Update Template</li>
        </ol>
    </div>
    <!--breadcrumb wrap close-->

    <!--Filter Section -->
    <div class="section">
        <div class="form-item-title clearfix">
            <h3 class="title">Update Template</h3>
        </div>
        <!-- title and form upper action end-->
         <?php echo form_open_multipart();?>
            <div class="row">
                <div class="col-lg-12 col-sm-12">
                    <label class="admin-label"><br></label>
                    <!-- thumb wrapper -->
                    <!--<div class="image-view-wrapper img-view200p img-viewbdr-radius4p">
                        <div class="image-view img-view200" id="profilePic" style="background-image:url('<?php echo (isset($editdata['admin_profile_pic']) && !empty($editdata['admin_profile_pic'])) ? base_url() . 'public/adminpanel/admin/' . $editdata['admin_profile_pic'] : '' ?>');">
                            <a href="javascript:void(0);" class="upimage-btn">
                            <input type="file" id="upload" style="display:none;" accept="image/*" name="admin_image" onchange="loadFile_signup(event,'profilePic', this)">
                            </a>
                            <label class="camera" for="upload"><i class="fa fa-camera" aria-hidden="true"></i></label>
                            <label id="image-error" class="alert-danger"></label>
                        </div>
                        <span class="loder-wrraper-single"></span>
                    </div>-->
                    
                    <!-- cropper image preview box start-->
                    <div class="image-view-wrapper img-view200p img-viewbdr-radius4p">
                        <div class="image-view img-view200">
                            <div class="photo-upload-here">
                                <img style="width: 100%;height: 100%;" class="profile-pic" id="profile_image" src="<?php echo (!empty($editdata['admin_profile_pic']))?$editdata['admin_profile_pic']:DEFAULT_IMAGE ?>">
                            </div>

                            <div class="image_upload_trigger" onclick="addCoverImage()">
                                <a href="javascript:void(0);" class="upimage-btn">
                                </a>
                                <label class="camera" for="upload"><i class="fa fa-camera" aria-hidden="true"></i></label>
                                <input type="hidden" name="imgurl" class="inputhidden">
                                <input type="hidden" id="imgChange" name="imgChange" value="">
                            </div>
                        </div>
                    </div>
                    <!-- cropper image preview box end-->
                
                    <!-- //thumb wrapper -->
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label class="admin-label">Select Room Type</label>
                        <div class="display">
                            <select name="room_type" class="form-control platform">
                                <option value="">Select</option>
                            </select>
                            <span class=""></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label class="admin-label">Select Lighting</label>
                        <div class="display">
                            <select name="lighting" class="form-control platform">
                                <option value="">Select</option>
                            </select>
                            <span class=""></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label class="admin-label">Select Category</label>
                        <div class="display">
                            <select name="category" class="form-control platform">
                                <option value="">Select</option>
                            </select>
                            <span class=""></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label for="" class="admin-label">Room Length</label>
                        <div class="col-md-8 col-sm-6 col-xs-6">
                            <div class="input-holder">
                                <input type="text" class="form-control" name="room_length" placeholder="0.00">
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-6">
                            <div class="input-holder">
                                <select class="form-control" name="room_length_unit" placeholder="Unit">
                                    <option value="Meter">Meter</option>
                                    <option value="Yards">Yards</option>
                                    <option value="Inches">Inches</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label for="" class="admin-label">Room Breath</label>
                        <div class="col-md-8 col-sm-6 col-xs-6">
                            <div class="input-holder">
                                <input type="text" class="form-control" name="room_breath" placeholder="0.00">
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-6">
                            <div class="input-holder">
                                <select class="form-control" name="room_breath_unit" placeholder="Unit">
                                    <option value="Meter">Meter</option>
                                    <option value="Yards">Yards</option>
                                    <option value="Inches">Inches</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label for="" class="admin-label">Room Height</label>
                        <div class="col-md-8 col-sm-6 col-xs-6">
                            <div class="input-holder">
                                <input type="text" class="form-control" name="room_height" placeholder="0.00">
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-6">
                            <div class="input-holder">
                                <select class="form-control" name="room_height_unit" placeholder="Unit">
                                    <option value="Meter">Meter</option>
                                    <option value="Yards">Yards</option>
                                    <option value="Inches">Inches</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label for="" class="admin-label">Workplane Height</label>
                        <div class="col-md-8 col-sm-6 col-xs-6">
                            <div class="input-holder">
                                <input type="text" class="form-control" name="workplane_height" placeholder="0.00">
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-6">
                            <div class="input-holder">
                                <select class="form-control" name="workplane_height_unit" placeholder="Unit">
                                    <option value="Meter">Meter</option>
                                    <option value="Yards">Yards</option>
                                    <option value="Inches">Inches</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label for="" class="admin-label">Room Shape</label>
                        <div class="display">
                            <select name="lighting" class="form-control platform">
                                <option value="">Select</option>
                            </select>
                            <span class=""></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label for="" class="admin-label">Lux Value</label>
                        <div class="input-holder">
                            <input type="text" class="form-control" name="lux_value" placeholder="0.00">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="button-wrap">
                    <input type="submit" class="commn-btn save" value="Add Room">
                </div>
            </div>

            <?php echo form_close();?>
            
    </div>
</div>