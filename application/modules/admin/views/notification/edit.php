<!-- Include Required Prerequisites -->
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<!-- Include Date Range Picker -->
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />

<div class="inner-right-panel">
    <!--breadcrumb wrap-->
<div class="breadcrumb-wrap">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url() ?>admin/notification">Notifications</a></li>
        <li class="breadcrumb-item active">Edit Notification</li>
    </ol>
</div>
<!--breadcrumb wrap close-->
<!--Filter Section -->
    <div class="section">
    <!--Filter Section -->
        <div class="form-item-title clearfix">
            <h3 class="title">Edit Notification</h3>
        </div>
    <?php //echo '<pre>'; print_r($detail); die; ?>
        <!-- title and form upper action end-->       
            <?php echo form_open_multipart('admin/notification/edit?id=' . $notification_id);?>            

                <div class="row">
                    <div class="col-lg-3 col-sm-4">
                        <label class="admin-label"><br></label>
                        <!--<div class="image-view-wrapper img-view200p img-viewbdr-radius4p">
                            <div class="profile-pic" id="profilePic" style="background-image:url(<?php echo (isset($editdata['admin_profile_pic']) && !empty($editdata['admin_profile_pic'])) ? base_url() . 'public/adminpanel/admin/' . $editdata['admin_profile_pic'] : '' ?>);">
                                <span href="javascript:void(0);" class="upimage-btn">
                           
                                </span>
                                <input type="file" id="upload" style="display:none;" accept="image/*" name="notificationImage">
                                 <label class="camera" for="upload"><i class="fa fa-camera" aria-hidden="true"></i></label>   

                                <label id="image-error" class="alert-danger"></label>
                            </div>
                        </div>-->
                        <!-- cropper image preview box start-->
                    <div class="image-view-wrapper img-view200p img-viewbdr-radius4p">
                        <div class="image-view img-view200">
                            <div class="photo-upload-here">
                                <img style="width: 100%;height: 100%;" class="profile-pic" id="profile_image" src="<?php echo (!empty($detail['image']))?$detail['image']:DEFAULT_IMAGE ?>">
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
                    </div>
                    <input type="hidden" value="<?php echo $detail['id'] ?>" name="notiId">
                    <span class="loder-wrraper-single"></span>
                    <div class="col-sm-9">  
                        <div class="row">  
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="admin-label">Title</label>
                                    <div class="input-holder">
                                        <input type="text" name="title" name="title" value="<?php echo $detail['title'] ?>" id="title" placeholder="Notification title">
                                        <span class="titleErr error"></span>
                                    </div>

                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="admin-label">External Link</label>
                                    <div class="input-holder">
                                        <input type="text" name="link" value="<?php echo $detail['link'] ?>" id="link" placeholder="Enter link">
                                    </div>

                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="admin-label">Message</label>
                                    <div class="input-holder">
                                        <textarea class="custom-textarea" style="resize:none;" maxlength="255" name="message" id="message-text"><?php echo $detail['message'] ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="section-title">
                    <h3>Select Users</h3>
                </div>

                <!--Filter Section -->

                <div class="fltr-srch-wrap clearfix">
                    <div class="row">
                        <div class="col-lg-4 col-sm-4">
                            <div class="display form-group">
                                <select name="platform" class="selectpicker platform">
                                    <option value="">Select Platform</option>
                                    <option <?php echo ($detail['platform'] == '1')?'Selected':'' ?> value="1">All</option>
                                    <option <?php echo ($detail['platform'] == '2')?'Selected':'' ?> value="2">Android</option>
                                    <option <?php echo ($detail['platform'] == '3')?'Selected':'' ?> value="3">iOS</option>
                                </select>
                                <span class="platformErr error"></span>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-4">
                            <div class="display form-group">
                                <input type="text" id="regDate" value="<?php echo $detail['date_range'] ?>" class="regDate" name="regDate" placeholder="Select Date Range">
                            </div>
                        </div>
                        <div class="col-lg-12 text-center">
                            <div class="button-wrap">
                                <input type="submit"  class="commn-btn save" onclick="return checkNotiValidation()" value="Send Now">
                                <input type="button" onclick="history.go(-1)" class="commn-btn cancel" value="Cancel">
                            </div>
                        </div>
                    </div>
                </div>
                <!--Filter Section Close-->
                
            <?php echo form_close();?>
    </div>
</div>

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
<script>
    $(document).ready(function () {
        $('#regDate').daterangepicker(
         {
            locale: {
            format: 'DD/MM/YYYY'
            },
            autoApply:true
        }
      );
      <?php if(empty($detail['date_range'])){?>
        $('#regDate').val('');
      <?php } ?>
    })

</script>