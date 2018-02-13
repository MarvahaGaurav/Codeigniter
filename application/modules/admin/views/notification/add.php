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
            <li class="breadcrumb-item active">Add Notification</li>
        </ol>
    </div>
    <!--breadcrumb wrap close-->

    <!--Filter Section -->
    <div class="section">

        <!-- title and form upper action end-->
         <?php echo form_open_multipart();?>
            <div class="row">
                <div class="col-lg-3 col-sm-4">
                    <!-- thumb wrapper -->
                    <div class="image-view-wrapper img-view200p img-viewbdr-radius4p">
                        <div class="image-view img-view200" id="profilePic" style="background-image:url('<?php echo (isset($editdata['admin_profile_pic']) && !empty($editdata['admin_profile_pic'])) ? base_url() . 'public/adminpanel/admin/' . $editdata['admin_profile_pic'] : '' ?>');">
                            <a href="javascript:void(0);" class="upimage-btn">
                            <input type="file" id="upload" style="display:none;" accept="image/*" name="admin_image" onchange="loadFile_signup(event,'profilePic', this)">
                            </a>
                            <label class="camera" for="upload"><i class="fa fa-camera" aria-hidden="true"></i></label>
                            <label id="image-error" class="alert-danger"></label>
                        </div>
                        <span class="loder-wrraper-single"></span>
                    </div>
                    <!-- //thumb wrapper -->
                    <!-- <div class="form-profile-pic-wrapper">
                        <div class="profile-pic" id="profilePic"  style="background-image:url(<?php //echo (isset($editdata['admin_profile_pic']) && !empty($editdata['admin_profile_pic'])) ? base_url() . 'public/adminpanel/admin/' . $editdata['admin_profile_pic'] : '' ?>);">
                            <span href="javascript:void(0);" class="upimage-btn"></span>
                            <input type="file" id="upload" style="display:none;" accept="image/*" name="notificationImage">
                            <label class="camera" for="upload"><i class="fa fa-camera" aria-hidden="true"></i></label>   
                            <label id="image-error" class="alert-danger"></label>
                        </div>
                    </div> -->
                </div>

                <div class="col-lg-9 col-sm-8">
                    <div class="row">
                        <div class="col-sm-6 col-xs-6">
                            <div class="form-group">
                                <label class="admin-label">Title</label>
                                <div class="input-holder">
                                    <input type="text" name="title" name="title" id="title" placeholder="Notification title">
                                        <?php echo form_error('title','<label class="error">','</label>');?>
                                    <span class="titleErr error"></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-xs-6">
                            <div class="form-group">
                                <label class="admin-label">External Link</label>
                                <div class="input-holder">
                                    <input type="text" name="link" id="link" placeholder="Enter link">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="admin-label">Message</label>
                                <div class="input-holder">
                                    <textarea class="custom-textarea" style="resize:none;" maxlength="255" name="message" id="messagetext"></textarea>
                                    <?php echo form_error('messagetext','<label class="alert-danger">','</label>');?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="wrap-insection">
                <div class="section-title">
                    <h3>Select Users</h3>
                </div>

                <!--Filter Section -->
                <div class="fltr-srch-wrap clearfix">
                    <div class="row">
                        <div class="col-lg-4 col-sm-4">
                            <div class="display">
                                <select name="platform" class="form-control platform">
                                    <option value="">Select Platform</option>
                                    <option value="1">All</option>
                                    <option value="2">Android</option>
                                    <option value="3">iOS</option>
                                </select>
                                <span class="platformErr error"></span>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-4">
                            <div class="display showcalendar-up">
                                <input type="text" id="regDate" class="regDate" name="regDate" placeholder="Select Date Range">
                            </div>

                        </div>
                        <div class="col-lg-4 col-sm-4">
                            <div class="display">
                                <select name="gender" class="form-control gender">
                                    <option value="">Select Gender</option>
                                    <option value="1">Male</option>
                                    <option value="2">Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="button-wrap">
                                <input type="submit" onclick="return checkNotiValidation()" class="commn-btn save" value="Send Now">
                                <input type="button" onclick="return checkNotiValidation()" class="commn-btn cancel" value="Cancel">
                            </div>
                        </div>
                    </div>
                </div>
                <!--Filter Section Close-->

              <?php echo form_close();?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {

        $('#upload').change(function(){
            var file = this.files[0];
            var reader = new FileReader();
            reader.onloadend = function () {
               $('#profilePic').css('background-image', 'url("' + reader.result + '")');
            }
            if (file) {
                reader.readAsDataURL(file);
            } else {
                console.log('not done');
            }
        });
        
        $('#regDate').daterangepicker(
            {
                locale: {
                    format: 'DD/MM/YYYY'
                },
                autoApply:true,
                drops: 'up'
            }
        );
        $('#regDate').val('');
    })

</script>