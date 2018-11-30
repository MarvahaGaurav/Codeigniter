<!--breadcrumb wrap-->
<div class="breadcrumb-wrap">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url()?>admin/dashboard">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?php echo base_url()?>admin/profile">Admin Profile</a></li>
        <li class="breadcrumb-item active">Admin Edit Profile</li>
    </ol>
</div>
<!--breadcrumb wrap close-->
<div class="inner-right-panel">
    <!--Filter Section -->
    <?php echo form_open_multipart('', array('id'=>'editadminprofile1'));?>
        <div class="form-item-wrap">
            <div class="form-item-title clearfix">
                <h3 class="title">Admin Edit Profile</h3>
            </div>

            <!-- title and form upper action end-->
            <div class="form-ele-wrapper clearfix">



                <div class="row">
                  <div class="col-lg-4 col-sm-4">

<!--                        <div class="profile-wrap ">
          <div id='profile-holder' class="profile-holder-wrap "> <img class='profilePic ' src='<?php //echo (isset($editdata['profile_picture']) && !empty($editdata['profile_picture'])) ? $editdata['profile_picture'] : '' ?>'>
              <div class='profile-holder-action'>
                  <input id="upload" type="file" style="display:none" accept="image/*">

              </div>
          </div>
          <div id="profile-image-status"></div>
      </div>-->
            <div class="col-lg-4 col-sm-4">
              <div class="form-profile-pic-wrapper">
                  <div class="profile-pic" id="profilePic" style="background-image:url(<?php echo (isset($editdata['admin_profile_pic']) && !empty($editdata['admin_profile_pic'])) ? base_url().'public/adminpanel/admin/'.$editdata['admin_profile_pic'] : '' ?>);">
                      <a href="javascript:void(0);" class="upimage-btn">
                          <img src="public/adminpanel/images/camera.svg">
                          <input type="file" id="upload" accept="image/*" name="admin_image" onchange="loadFile_signup(event,'profilePic', this)">

                      </a>
                      <label id="image-error" class="alert-danger"></label>
                  </div>
              </div>
          </div>
                      <span class="loder-wrraper-single"></span>
                  </div>


                  <div class="col-sm-6 col-xs-6">
                      <div class="form-group">
                          <label class="admin-label">Name</label>
                          <div class="input-holder">
                              <input type="text" name="Admin_Name" id="Admin_Name" value="<?php echo (isset($editdata['admin_name']) && !empty($editdata['admin_name'])) ? $editdata['admin_name'] : set_value('Admin_Name'); ?>" placeholder="john samth">
                                <?php echo form_error('Admin_Name', '<label class="alert-danger">', '</label>');?>

                          </div>

                      </div>
                  </div>

                  <div class="col-sm-6 col-xs-6">
                      <div class="form-group">
                          <label class="admin-label">Email ID</label>
                          <div class="input-holder">
                              <input type="text" name="email" value="<?php echo (isset($editdata['admin_email']) && !empty($editdata['admin_email'])) ? $editdata['admin_email'] : set_value('email'); ?>" id="email" placeholder="RFID120@gmail.com">
                                <?php echo form_error('email', '<label class="alert-danger">', '</label>');?>
                          </div>

                      </div>
                  </div>

                  <div class="col-sm-6 col-xs-6">
                        <div class="form-group">
                          <label class="admin-label">Mobile Number</label>
                          <div class="input-holder">
                              <input type="text" name="mobile_number" value="<?php echo (isset($editdata['phone']) && !empty($editdata['phone'])) ? $editdata['phone'] : set_value('phone') ?>" placeholder="+9145785566">
                                <?php echo form_error('mobile_number', '<label class="alert-danger">', '</label>');?>
                          </div>

                      </div>
                  </div>

                </div>
                <div class="form-ele-action-bottom-wrap btns-center clearfix">
                    <div class="button-wrap text-center">
                        <button type="button"  onclick="window.location.href='<?php echo base_url()?>admin/profile'"class="commn-btn cancel">Cancel</button>
                        <button type="submit" class="commn-btn save">Save</button>
                    </div>
                </div>
                <!--form ele wrapper end-->
            </div>
            <!--form element wrapper end-->

        </div>
        <!--close form view   -->

    <?php echo form_close();?>
    <!--Filter Section Close-->
</div>
<!--Table listing-->
