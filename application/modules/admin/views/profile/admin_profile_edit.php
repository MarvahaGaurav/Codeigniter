<div class="inner-right-panel">
<!--breadcrumb wrap-->
<div class="breadcrumb-wrap">
   <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?php echo base_url()?>admin/dashboard">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="<?php echo base_url()?>admin/profile">Admin Profile</a></li>
      <li class="breadcrumb-item active">Admin Edit Profile</li>
   </ol>
</div>
<!--breadcrumb wrap close-->
<!--Filter Section -->
<?php echo form_open_multipart('', array('id'=>'editadminprofile1'));?>

    <div class="section">
        <div class="user-detail-panel">
            <div class="row">
            <?php //print_r($editdata); ?>
            <?php echo $this->session->flashdata('message'); ?>
            <div class="col-lg-3 col-sm-3">
                <!-- thumb wrapper -->


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
                <span class="loder-wrraper-single"></span>
                <!-- <div class="form-profile-pic-wrapper">
                    <div class="profile-pic" id="profilePic" style="background-image:url('<?php// echo (!empty($editdata['admin_profile_pic']))?IMAGE_PATH.$editdata['admin_profile_pic']:DEFAULT_IMAGE ?>');">
                        <a href="javascript:void(0);" class="upimage-btn">
                        <input type="file" id="upload" style="display:none;" accept="image/*" name="admin_image" onchange="loadFile_signup(event,'profilePic', this)">
                        </a>
                        <label class="camera" for="upload"><i class="fa fa-camera" aria-hidden="true"></i></label>
                        
                        <label id="image-error" class="alert-danger"><?php // echo (isset($imageErr) && !empty(imageErr)) ? $imageErr : ''; ?></label>
                        <label id="image-error" class="alert-danger"><?php // echo (isset($editdata['imageErr']) && !empty($editdata['imageErr'])) ? $editdata['imageErr'] : ''; ?></label>
                    </div>
                </div> -->
            </div>

            <div class="col-lg-9 col-sm-9 col-xs-12">
                <div class="user-detail-panel">
                    <div class="row">
                        <div class="col-lg-6 col-sm-6">
                            <div class="form-group">
                                <label class="admin-label">Name</label>
                                <div class="input-holder">
                                    <input type="text" maxlength="100" name="Admin_Name" id="Admin_Name" value="<?php echo (isset($editdata['admin_name']) && !empty($editdata['admin_name'])) ? $editdata['admin_name'] : set_value('Admin_Name'); ?>" placeholder="Enter Name">
                                    <?php echo form_error('Admin_Name', '<label class="alert-danger">', '</label>');?>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-lg-6 col-sm-6">
                            <div class="form-group">
                                <label class="admin-label">Email ID</label>
                                <div class="input-holder">
                                    <input type="text" readonly maxlength="100" name="email" value="<?php echo (isset($editdata['admin_email']) && !empty($editdata['admin_email'])) ? $editdata['admin_email'] : set_value('email'); ?>" id="email" placeholder="Enter Email">
                                    <?php echo form_error('email', '<label class="alert-danger">', '</label>');?>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-lg-12">
                            <div class="button-wrap text-left">
                                <button type="button"  onclick="window.location.href='<?php echo base_url()?>admin/profile'"class="commn-btn cancel">Cancel</button>
                                <button type="submit" class="commn-btn save">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>

    </div>
    <!--close form view   -->
    <?php echo form_close();?>
    <!--Filter Section Close-->
</div>
<!--Table listing-->

<!--cropper libraries-->
<link href="public/cropper/cropper.min.css" rel="stylesheet">
<script>
    if (location.hostname == "localhost") {
        var domain = location.protocol + '//' + location.hostname + (location.port ? ':' + location.port : '') + '/smartguide/admin';
        var domain2 = location.protocol + '//' + location.hostname + (location.port ? ':' + location.port : '') + '/smartguide';
    }
    else {
        var domain = location.protocol + '//' + location.hostname + (location.port ? ':' + location.port : '') + '/admin';
        var domain2 = location.protocol + '//' + location.hostname + (location.port ? ':' + location.port : '');
    }

</script>
<script src="public/cropper/cropper.js"></script>
<script src="public/cropper/cropper.min.js"></script>
<script src="public/cropper/main.js"></script>
<script>
    function addCoverImage() {
        callme('coverPicInput','640','640','imagepicker2','addshopbtn','imageMe1','true','','circular');
    }
</script>

<style>
    .myloader{
        width: 16%;
        position: absolute;
        margin-top: -29px;
        /*display: none;*/
    }
</style>
