
            <div class="breadcrumb-main-wrap clearfix">
                <!--breadcrumb  start-->
                <div class="breadcrumb-wrap">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="admin/Dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active">Change Password</li>
                    </ol>
                </div>
                <!--breadcrumb end-->
            </div>
            <!--breadcrumb-main-wrap-->
                   <form method="post" action="admin/Admin_Profile/admin_change_password?user=<?php  echo encrypt_with_openssl(new Encryption\OpenSSLEncrypt($editdata['admin_id']),true);?>" name="changepassword" id="changepassword">
                    <input type="hidden" name="<?php echo $csrfName; ?>" id="<?php echo $csrfName; ?>" value="<?php echo $csrfToken; ?>">
                    <input type="hidden" name="userid" id="userid" value="<?php  echo encrypt_with_openssl(new Encryption\OpenSSLEncrypt($editdata['admin_id']),true);?>">
            <div class="main-panel">
                <!--container main wrapper-->
                <div class="mycart-wrapper">
<!--                    <div class="my-profile-form clearfix">-->
                        <div class="password-wrapper">
                            <div class="total-rsv-wrap">
                                <h1>Change Password</h1>
                            </div>
                            <div class="row">
                                <div class="field-wrap">
                                     <div class="col-lg-5 col-sm-5 col-xs-6">
                                        <label class="admin-label">Old Password</label>
                                    </div>
                                  <div class="input-wrapper">
                                      <input type="password" name="oldpassword" id="oldpassword" value="<?php echo set_value('oldpassword')?>" placeholder="********" maxlength="30">
                                                </div>
                                   <div class="error"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="field-wrap">
                                     <div class="col-lg-5 col-sm-5 col-xs-6">
                                        <label class="admin-label">New Password</label>
                                    </div>
                                  <div class="input-wrapper">
                                      <input type="password" name="password" id="password" value="<?php echo set_value('password')?>" placeholder="********" maxlength="30">
                                                </div>
                                                <div class="error"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="field-wrap">
                                  <div class="col-lg-5 col-sm-5 col-xs-6">
                                        <label class="admin-label">Confirm New Password</label>
                                    </div>
                                            <div class="input-wrapper">
                                                <input type="password" name="confirm_password" id="confirm-password" value="<?php echo set_value('confirm_password')?>" placeholder="********" maxlength="30">
                                  </div>
                                      <div class="error"></div>
                                </div>
                            </div>
                                   <div class="col-lg-12 col-sm-12  text-center">
                                        <a href="admin/Admin_Profile/admin_profile"> <button type="button" class="add-bttn cancel">Cancel</button></a>
                                        <button class="add-bttn  bg-color">Submit</button>
                                    </div>

                        </div>
                    </div>
                </div>
                <!--container main wrapper-->

                <!--container close   -->

            </div>
                    </form>
            </div>
        </div>
            <!--Footer-->
        </div>
         <div id="maps-modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-log">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header custom-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title modal-heading">Requesting</h4>
                </div>
                <div class="modal-body">
                  <div id="google-maps-box">
                      <textarea type="text"  id='location1' value="" style="width:87%;"></textarea>
                    <div class="map-box">
                        <div id="map" style="width:87%;height:300px;"></div>
                            
                    </div>
                </div>
                  
                </div>

            </div>
        </div>
        
    </div>
        <!--data  Wrap close-->  
        