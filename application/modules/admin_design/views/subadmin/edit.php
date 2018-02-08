<link href="public/css/form-roles.css" rel='stylesheet'>

<?php
$userPermission = isset($permission[1]) ? $permission[1] : array();
$versionPermission = isset($permission[2]) ? $permission[2] : array();
$notiPermission = isset($permission[3]) ? $permission[3] : array();
?>
<body>
    <!-- Content -->
    <section class="inner-right-panel clearfix">

        <!--breadcrumb wrap-->
        <div class="breadcrumb-wrap">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin/subadmin">Sub-admin</a></li>
                <li class="breadcrumb-item"><a href="admin/subadmin/view">Sub-Admin Profile</a></li>
                <li class="active">Edit Sub-Admin Profile</li>
            </ol>
        </div>	
        <div class="clear"></div>


        <div class="section">

            <!-- form-->
            <?php echo form_open_multipart('', array('id' => 'subadmin_add')); ?>
            <input type='hidden' value='<?php echo encryptDecrypt($admin_id); ?>' name='token' >
            <div class="row">
                <div class="col-sm-3 col-xs-12">
                    <div class="image-view-wrapper img-view200p img-viewbdr-radius4p img-mb">
                        <div class="image-view img-view200" id="profilePic" style="background-image:url('<?php echo (!empty($editdata['admin_profile_pic']))?IMAGE_PATH.$editdata['admin_profile_pic']:DEFAULT_IMAGE ?>');">
                            <a href="javascript:void(0);" class="upimage-btn">
                            <input type="file" id="upload" style="display:none;" accept="image/*" name="admin_image" onchange="loadFile_signup(event,'profilePic', this)">
                            </a>
                            <label class="camera" for="upload"><i class="fa fa-camera" aria-hidden="true"></i></label>
                            <label id="image-error" class="alert-danger"></label>
                        </div>
                    </div>
                    <span class="loder-wrraper-single"></span>
                </div>
                <div class="col-sm-9 col-xs-12">
                    <div class="row">
                        <div class="user-detail-panel">
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="admin-label">Name</label>
                                    <div class="input-holder">
                                        <!-- <input type="text" class="" maxlength="100" value="<?php //echo $admindetail['admin_name'] ?>" name="name" placeholder="* Sub-admin Name" value="<?php //echo set_value('name'); ?>"> -->
                                        <input type="text" class="" value="" name="name" placeholder="* Sub-admin Name">
                                        <?php echo form_error('name', '<label class="alert-danger">', '</label>'); ?>   
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="admin-label">Mobile Number</label>
                                    <div class="input-holder">
                                        <input type="number" class="" maxlength="100" value="" placeholder="Mobile Number">
                                        <?php echo form_error('email', '<label class=" alert-danger">', '</label>'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="admin-label">Email ID</label>
                                    <div class="input-holder">
                                        <!-- <input type="text" class="" maxlength="100" value="<?php //echo $admindetail['admin_email'] ?>" name="email" placeholder="* Sub-admin Email" value="<?php //echo set_value('email'); ?>"> -->
                                        <input type="text" class="" value="" name="name" placeholder="Email Address">
                                        <?php echo form_error('email', '<label class=" alert-danger">', '</label>'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="admin-label">Password</label>
                                    <div class="input-holder">
                                        <input type="text" class="" maxlength="16" name="newpassword" placeholder="* New Password">
                                        <?php echo form_error('password', '<label class=" alert-danger">', '</label>'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="admin-label admin-label-access">Access To</label>
                                    <div class="input-holder">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="custom-check main-check">
                                                    <input id="main-check1" name="user" onchange="permission('user')"  value="1" <?php echo (!empty($userPermission)) ? 'checked' : '' ?> type="checkbox">
                                                    <label for="main-check1"><span></span>User Management</label>
                                                    <ul class="check-column">
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck1-1" name="permission[user][view]" <?php echo (!empty($userPermission['viewp'])) ? 'checked' : '' ?> <?php echo (!empty($userPermission)) ? '' : 'disabled="true"' ?> value="1" class="user" type="checkbox" >
                                                                <label for="subcheck1-1"><span></span>View </label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck1-2" name="permission[user][view]" <?php echo (!empty($userPermission['viewp'])) ? 'checked' : '' ?> <?php echo (!empty($userPermission)) ? '' : 'disabled="true"' ?> value="1" class="user" type="checkbox" >
                                                                <label for="subcheck1-2"><span></span>Add </label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck1-3" name="permission[user][view]" <?php echo (!empty($userPermission['viewp'])) ? 'checked' : '' ?> <?php echo (!empty($userPermission)) ? '' : 'disabled="true"' ?> value="1" class="user" type="checkbox" >
                                                                <label for="subcheck1-3"><span></span>Edit </label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck1-4" name="permission[user][block]" <?php echo (!empty($userPermission['blockp'])) ? 'checked' : '' ?> value="1"  <?php echo (!empty($userPermission)) ? '' : 'disabled="true"' ?> class="user" type="checkbox" >
                                                                <label for="subcheck1-4"><span></span>Block  </label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck1-5" name="permission[user][delete]" <?php echo (!empty($userPermission['deletep'])) ? 'checked' : '' ?> value="1" class="user" <?php echo (!empty($userPermission)) ? '' : 'disabled="true"' ?> type="checkbox" >
                                                                <label for="subcheck1-5"><span></span>Delete</label>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>			
                                                <div class="clear"></div>
                                                <div class="custom-check main-check">
                                                    <input id="main-check2" name="version" <?php echo (!empty($versionPermission)) ? 'checked' : '' ?> onchange="permission('Version')" value="2" type="checkbox">
                                                    <label for="main-check2"><span></span>Merchant Management </label>
                                                    <ul class="check-column">
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck2-1" name="permission[version][add]" <?php echo (!empty($versionPermission['addp'])) ? 'checked' : '' ?> value="1"  <?php echo (!empty($versionPermission)) ? '' : 'disabled="true"' ?> class="Version" type="checkbox">
                                                                <label for="subcheck2-1"><span></span>View</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck2-2" name="permission[version][add]" <?php echo (!empty($versionPermission['addp'])) ? 'checked' : '' ?> value="1"  <?php echo (!empty($versionPermission)) ? '' : 'disabled="true"' ?> class="Version" type="checkbox">
                                                                <label for="subcheck2-2"><span></span>Add</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck2-3" name="permission[version][edit]" <?php echo (!empty($versionPermission['editp'])) ? 'checked' : '' ?> value="1" <?php echo (!empty($versionPermission)) ? '' : 'disabled="true"' ?> class="Version" type="checkbox">
                                                                <label for="subcheck2-3"><span></span>Edit </label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck2-4" name="permission[version][edit]" <?php echo (!empty($versionPermission['editp'])) ? 'checked' : '' ?> value="1" <?php echo (!empty($versionPermission)) ? '' : 'disabled="true"' ?> class="Version" type="checkbox">
                                                                <label for="subcheck2-4"><span></span>Block </label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck2-5" name="permission[version][delete]" <?php echo (!empty($versionPermission['deletep'])) ? 'checked' : '' ?> value="1" <?php echo (!empty($versionPermission)) ? '' : 'disabled="true"' ?> class="Version" type="checkbox">
                                                                <label for="subcheck2-5"><span></span>Delete </label>
                                                            </div>
                                                        </li>

                                                    </ul>
                                                </div>			
                                                <div class="clear"></div>
                                                <div class="custom-check main-check">
                                                    <input id="main-check3" name="notification" onchange="permission('Notification')" <?php echo (!empty($notiPermission)) ? 'checked' : '' ?> value="3"  type="checkbox">
                                                    <label for="main-check3"><span></span>Product Management</label>
                                                    <ul class="check-column">
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck3-1"  name="permission[notification][add]" <?php echo (!empty($notiPermission['addp'])) ? 'checked' : '' ?> value="1" <?php echo (!empty($notiPermission)) ? '' : 'disabled="true"' ?> class="Notification"  type="checkbox">
                                                                <label for="subcheck3-1"><span></span>View </label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck3-2"  name="permission[notification][add]" <?php echo (!empty($notiPermission['addp'])) ? 'checked' : '' ?> value="1" <?php echo (!empty($notiPermission)) ? '' : 'disabled="true"' ?> class="Notification"  type="checkbox">
                                                                <label for="subcheck3-2"><span></span>Add </label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck3-3"  name="permission[notification][edit]" <?php echo (!empty($notiPermission['editp'])) ? 'checked' : '' ?> value="1" <?php echo (!empty($notiPermission)) ? '' : 'disabled="true"' ?> class="Notification"  type="checkbox">
                                                                <label for="subcheck3-3"><span></span>Edit</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck3-4"  name="permission[notification][edit]" <?php echo (!empty($notiPermission['editp'])) ? 'checked' : '' ?> value="1" <?php echo (!empty($notiPermission)) ? '' : 'disabled="true"' ?> class="Notification"  type="checkbox">
                                                                <label for="subcheck3-4"><span></span>Edit or Resend</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck3-5"  name="permission[notification][delete]" <?php echo (!empty($notiPermission['deletep'])) ? 'checked' : '' ?> value="1" <?php echo (!empty($notiPermission)) ? '' : 'disabled="true"' ?> class="Notification"  type="checkbox">
                                                                <label for="subcheck3-5"><span></span>Delete</label>
                                                            </div>
                                                        </li>				
                                                    </ul>
                                                </div>
                                                <div class="clear"></div>
                                                <div class="custom-check main-check">
                                                    <input id="main-check4" name="notification" onchange="permission('Notification')" <?php echo (!empty($notiPermission)) ? 'checked' : '' ?> value="3"  type="checkbox">
                                                    <label for="main-check4"><span></span>Template Management</label>
                                                    <ul class="check-column">
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck4-1"  name="permission[notification][add]" <?php echo (!empty($notiPermission['addp'])) ? 'checked' : '' ?> value="1" <?php echo (!empty($notiPermission)) ? '' : 'disabled="true"' ?> class="Notification"  type="checkbox">
                                                                <label for="subcheck4-1"><span></span>View </label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck4-2"  name="permission[notification][add]" <?php echo (!empty($notiPermission['addp'])) ? 'checked' : '' ?> value="1" <?php echo (!empty($notiPermission)) ? '' : 'disabled="true"' ?> class="Notification"  type="checkbox">
                                                                <label for="subcheck4-2"><span></span>Add </label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck4-3"  name="permission[notification][edit]" <?php echo (!empty($notiPermission['editp'])) ? 'checked' : '' ?> value="1" <?php echo (!empty($notiPermission)) ? '' : 'disabled="true"' ?> class="Notification"  type="checkbox">
                                                                <label for="subcheck4-3"><span></span>Edit</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck4-4"  name="permission[notification][edit]" <?php echo (!empty($notiPermission['editp'])) ? 'checked' : '' ?> value="1" <?php echo (!empty($notiPermission)) ? '' : 'disabled="true"' ?> class="Notification"  type="checkbox">
                                                                <label for="subcheck4-4"><span></span>Block</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck4-5"  name="permission[notification][delete]" <?php echo (!empty($notiPermission['deletep'])) ? 'checked' : '' ?> value="1" <?php echo (!empty($notiPermission)) ? '' : 'disabled="true"' ?> class="Notification"  type="checkbox">
                                                                <label for="subcheck4-5"><span></span>Delete</label>
                                                            </div>
                                                        </li>				
                                                    </ul>
                                                </div>
                                                <div class="clear"></div>
                                                <div class="custom-check main-check">
                                                    <input id="main-check5" name="notification" onchange="permission('Notification')" <?php echo (!empty($notiPermission)) ? 'checked' : '' ?> value="3"  type="checkbox">
                                                    <label for="main-check5"><span></span>Content Management</label>
                                                    <ul class="check-column">
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck5-1"  name="permission[notification][add]" <?php echo (!empty($notiPermission['addp'])) ? 'checked' : '' ?> value="1" <?php echo (!empty($notiPermission)) ? '' : 'disabled="true"' ?> class="Notification"  type="checkbox">
                                                                <label for="subcheck5-1"><span></span>View </label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck5-2"  name="permission[notification][add]" <?php echo (!empty($notiPermission['addp'])) ? 'checked' : '' ?> value="1" <?php echo (!empty($notiPermission)) ? '' : 'disabled="true"' ?> class="Notification"  type="checkbox">
                                                                <label for="subcheck5-2"><span></span>Add </label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck5-3"  name="permission[notification][edit]" <?php echo (!empty($notiPermission['editp'])) ? 'checked' : '' ?> value="1" <?php echo (!empty($notiPermission)) ? '' : 'disabled="true"' ?> class="Notification"  type="checkbox">
                                                                <label for="subcheck5-3"><span></span>Edit</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck5-4"  name="permission[notification][edit]" <?php echo (!empty($notiPermission['editp'])) ? 'checked' : '' ?> value="1" <?php echo (!empty($notiPermission)) ? '' : 'disabled="true"' ?> class="Notification"  type="checkbox">
                                                                <label for="subcheck5-4"><span></span>Block</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck5-5"  name="permission[notification][delete]" <?php echo (!empty($notiPermission['deletep'])) ? 'checked' : '' ?> value="1" <?php echo (!empty($notiPermission)) ? '' : 'disabled="true"' ?> class="Notification"  type="checkbox">
                                                                <label for="subcheck5-5"><span></span>Delete</label>
                                                            </div>
                                                        </li>				
                                                    </ul>
                                                </div>	
                                            </div>			
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="button-wrap">
                                    <button type="submit" class="commn-btn save">Update Profile</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php echo form_close(); ?>

</section>
<script>
    //function for give the permission to subadmin

    function permission(gettype) {
        var isdiabled = $('.' + gettype).prop('disabled');

        if (isdiabled) {
            $('.' + gettype).removeAttr('disabled', false);
        } else {
            $('.' + gettype).prop('disabled', true);
            $('.' + gettype).attr('checked', false);
        }
    }
</script>