<!--breadcrumb wrap-->
<div class="breadcrumb-wrap">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url()?>admin/dashboard">Dashboard</a></li>
        <li class="breadcrumb-item active">Admin Profile</li>
    </ol>
</div>
<!--breadcrumb wrap close-->
<div class="inner-right-panel">
    <!--Filter Section -->
    <?php
    if ($this->session->flashdata('message') != '') {

        echo $this->session->flashdata('message');
    }
    ?>
        <div class="form-item-wrap">
            <div class="form-item-title clearfix">
                <h3 class="title"> Profile</h3>
            </div>

            <!-- title and form upper action end-->
            <div class="form-ele-wrapper clearfix">



              <div class="col-lg-4 col-sm-4">

<!--                        <div class="profile-wrap ">
      <div id='profile-holder' class="profile-holder-wrap "> <img class='profilePic ' src='<?php echo (isset($editdata['profile_picture']) && !empty($editdata['profile_picture'])) ? $editdata['profile_picture'] : '' ?>'>
          <div class='profile-holder-action'>
              <input id="upload" type="file" style="display:none" accept="image/*">

          </div>
      </div>
      <div id="profile-image-status"></div>
  </div>-->
                    <div class="col-lg-4 col-sm-4">
          <div class="form-profile-pic-wrapper">
              <div class="profile-pic" style="background-image:url(<?php echo (isset($editdata['admin_profile_pic']) && !empty($editdata['admin_profile_pic'])) ?base_url().'public/adminpanel/admin/'.$editdata['admin_profile_pic'] : '' ?>);">
                  <!-- <a href="javascript:void(0);" class="upimage-btn">
                      <img >
                      <input type="file" id="upload" accept="image/*">
                  </a> -->
              </div>
          </div>
      </div>
                  <span class="loder-wrraper-single"></span>
              </div>
              <div class="col-lg-8 col-sm-8">
                  <div class="col-lg-6 col-sm-6">
                      <div class="form-group-wrapper">
                          <label>Name</label>
                          <div class="input-holder">
                              <span type="text" name="Merchant_Name" id="Merchant_Name" value="" placeholder="john samth"><?php echo (isset($editdata['admin_name']) && !empty($editdata['admin_name'])) ? $editdata['admin_name'] : '' ?></span>
                          </div>
                          <div class="error"><?php echo form_error('Merchant_Name')?></div>
                      </div>
                  </div>
                  <span class="error"></span>
                  <div class="col-lg-6 col-sm-6">
                      <div class="form-group-wrapper">
                          <label>Email ID</label>
                          <div class="input-holder">
                              <span type="text" name="email" value="" id="email" placeholder="RFID120@gmail.com"><?php echo (isset($editdata['admin_email']) && !empty($editdata['admin_email'])) ? $editdata['admin_email'] : '' ?></span>
                          </div>
                          <div class="error"><?php echo form_error('email')?></div>
                      </div>
                  </div>
                  <div class="col-lg-6 col-sm-6">
                      <div class="form-group-wrapper">
                          <label>Mobile Number</label>
                          <div class="input-holder">
                              <span type="text" name="mobile-number" id="mobile-number" value="" placeholder="+9145785566"><?php echo (isset($editdata['phone']) && !empty($editdata['phone'])) ? $editdata['phone'] : '' ?></span>
                          </div>
                          <div class="error"><?php echo form_error('mobile-number')?></div>
                      </div>
                  </div>

              </div>
              <div class="col-lg-12 col-sm-12  text-center">
                  <a href="admin/Dashboard"> <button type="button" class="add-bttn cancel">Cancel</button></a>
                  <a href="admin/change-password"><button type="button" name="changepassword" class="add-bttn bg-color">Change Password</button></a>
                  <a href="admin/edit-profile"><button type="button" name="editprofile" class="add-bttn bg-color">Edit Profile</button></a>
              </div>
                </div>
                <!--form ele wrapper end-->
            </div>
            <!--form element wrapper end-->

        </div>
        <!--close form view   -->


    <!--Filter Section Close-->
</div>
<!--Table listing-->
