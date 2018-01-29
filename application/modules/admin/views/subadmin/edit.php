<?php
$userPermission = isset($permission[1]) ? $permission[1] : array();
$versionPermission = isset($permission[2]) ? $permission[2] : array();
$notiPermission = isset($permission[3]) ? $permission[3] : array();
?>
<body>
    <!-- Content -->
    <section class="content-wrapper clearfix">
        <div class="upper-head-panel m-b-lg clearfix">
            <ul class="breadcrumb reward-breadcrumb">
                <li><a href="admin/subadmin">Sub Admins</a></li>
                <li class="active">Update Sub-admin</li>
            </ul>
        </div>	
        <div class="clear"></div>
        <div class="col-sm-12">
            <div class="adminRoles-wrapper">
                <div class="form-item-title clearfix">
                    <h3 class="title">Update the Sub-admin detail</h3>
                </div>
                <!-- title and form upper action end-->
                <?php echo form_open_multipart('', array('id' => 'subadmin_add')); ?>
                <input type='hidden' value='<?php echo encryptDecrypt($admin_id); ?>' name='token' >
                <div class="form-ele-wrapper clearfix">
                    <div class="row">
                        <div class="col-sm-6 col-xs-6">
                            <div class="form-group">
                                <label class="admin-label">Admin Name</label>
                                <div class="input-holder">
                                    <input type="text" class="form-control material-control" maxlength="100" value="<?php echo $admindetail['admin_name'] ?>" name="name" placeholder="* Sub-admin Name" value="<?php echo set_value('name'); ?>">
                                    <?php echo form_error('name', '<label class="alert-danger">', '</label>'); ?>   
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-6">
                            <div class="form-group">
                                <label class="admin-label">Email</label>
                                <div class="input-holder">
                                    <input type="text" class="form-control material-control" maxlength="100" value="<?php echo $admindetail['admin_email'] ?>" name="email" placeholder="* Sub-admin Email" value="<?php echo set_value('email'); ?>">
                                    <?php echo form_error('email', '<label class=" alert-danger">', '</label>'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-6">
                            <div class="form-group">
                                <label class="admin-label">Password</label>
                                <div class="input-holder">
                                    <input type="text" class="form-control material-control" maxlength="16" name="newpassword" placeholder="* New Password">
                                    <?php echo form_error('password', '<label class=" alert-danger">', '</label>'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-6">
                            <div class="form-group">
                                <label class="admin-label">Status</label>
                                <div class="commn-select-wrap">
                                    <select class="selectpicker" name="status">
                                        <option value="">Select</option>
                                        <option <?php echo ($admindetail['status'] == 1) ? 'Selected' : '' ?> value="1">Active</option>
                                        <option <?php echo ($admindetail['status'] == 2) ? 'Selected' : '' ?> value="2">Inactive</option>
                                    </select>
                                    <?php echo form_error('status', '<label class="alert-danger">', '</label>'); ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="adminRoles-wrapper p-md m-b-lg">
                                <div class="form-ele-wrapper clearfix">
                                    <div class="row">
                                        <div class="col-lg-12"><h2 class="title-box m-t-n p-t-20">Sub-admin Roles :</h2></div>
                                        <div class="col-lg-12">
                                            <div class="custom-check main-check">
                                                <input id="main-check1" name="user" onchange="permission('user')"  value="1" <?php echo (!empty($userPermission)) ? 'checked' : '' ?> type="checkbox">
                                                <label for="main-check1"><span></span>Manage User </label>
                                                <ul class="check-column">
                                                    <li>
                                                        <div class="custom-check">
                                                            <input id="subcheck1-1" name="permission[user][view]" <?php echo (!empty($userPermission['viewp'])) ? 'checked' : '' ?> <?php echo (!empty($userPermission)) ? '' : 'disabled="true"' ?> value="1" class="user" type="checkbox" >
                                                            <label for="subcheck1-1"><span></span>View </label>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="custom-check">
                                                            <input id="subcheck1-2" name="permission[user][block]" <?php echo (!empty($userPermission['blockp'])) ? 'checked' : '' ?> value="1"  <?php echo (!empty($userPermission)) ? '' : 'disabled="true"' ?> class="user" type="checkbox" >
                                                            <label for="subcheck1-2"><span></span>Block  </label>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="custom-check">
                                                            <input id="subcheck1-3" name="permission[user][delete]" <?php echo (!empty($userPermission['deletep'])) ? 'checked' : '' ?> value="1" class="user" <?php echo (!empty($userPermission)) ? '' : 'disabled="true"' ?> type="checkbox" >
                                                            <label for="subcheck1-3"><span></span>Delete</label>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>			
                                            <div class="clear"></div>
                                            <div class="custom-check main-check">
                                                <input id="main-check2" name="version" <?php echo (!empty($versionPermission)) ? 'checked' : '' ?> onchange="permission('Version')" value="2" type="checkbox">
                                                <label for="main-check2"><span></span>Manage Version</label>
                                                <ul class="check-column">
                                                    <li>
                                                        <div class="custom-check">
                                                            <input id="subcheck2-4" name="permission[version][add]" <?php echo (!empty($versionPermission['addp'])) ? 'checked' : '' ?> value="1"  <?php echo (!empty($versionPermission)) ? '' : 'disabled="true"' ?> class="Version" type="checkbox">
                                                            <label for="subcheck2-4"><span></span>Add</label>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="custom-check">
                                                            <input id="subcheck2-2" name="permission[version][edit]" <?php echo (!empty($versionPermission['editp'])) ? 'checked' : '' ?> value="1" <?php echo (!empty($versionPermission)) ? '' : 'disabled="true"' ?> class="Version" type="checkbox">
                                                            <label for="subcheck2-2"><span></span>Edit </label>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="custom-check">
                                                            <input id="subcheck2-3" name="permission[version][delete]" <?php echo (!empty($versionPermission['deletep'])) ? 'checked' : '' ?> value="1" <?php echo (!empty($versionPermission)) ? '' : 'disabled="true"' ?> class="Version" type="checkbox">
                                                            <label for="subcheck2-3"><span></span>Delete </label>
                                                        </div>
                                                    </li>

                                                </ul>
                                            </div>			
                                            <div class="clear"></div>
                                            <div class="custom-check main-check">
                                                <input id="main-check3" name="notification" onchange="permission('Notification')" <?php echo (!empty($notiPermission)) ? 'checked' : '' ?> value="3"  type="checkbox">
                                                <label for="main-check3"><span></span>Manage Notifications </label>
                                                <ul class="check-column">
                                                    <li>
                                                        <div class="custom-check">
                                                            <input id="subcheck3-1"  name="permission[notification][add]" <?php echo (!empty($notiPermission['addp'])) ? 'checked' : '' ?> value="1" <?php echo (!empty($notiPermission)) ? '' : 'disabled="true"' ?> class="Notification"  type="checkbox">
                                                            <label for="subcheck3-1"><span></span>Add </label>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="custom-check">
                                                            <input id="subcheck3-3"  name="permission[notification][edit]" <?php echo (!empty($notiPermission['editp'])) ? 'checked' : '' ?> value="1" <?php echo (!empty($notiPermission)) ? '' : 'disabled="true"' ?> class="Notification"  type="checkbox">
                                                            <label for="subcheck3-3"><span></span>Edit or Resend</label>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="custom-check">
                                                            <input id="subcheck3-4"  name="permission[notification][delete]" <?php echo (!empty($notiPermission['deletep'])) ? 'checked' : '' ?> value="1" <?php echo (!empty($notiPermission)) ? '' : 'disabled="true"' ?> class="Notification"  type="checkbox">
                                                            <label for="subcheck3-4"><span></span>Delete</label>
                                                        </div>
                                                    </li>				
                                                </ul>
                                            </div>			
                                        </div>			
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-xs-12">
                    <div class="form-ele-action-bottom-wrap btns-center clearfix">
                        <div class="button-wrap text-center">
                            <button type="button"  onclick="window.location.href = '<?php echo base_url() ?>admin/subadmin'"class="commn-btn cancel">Cancel</button>
                            <button type="submit" class="commn-btn save">Save</button>
                        </div>
                    </div>
                </div>
                <!--form ele wrapper end-->
                <!--close form view   -->
                <?php echo form_close(); ?>
                </section>
                </body>
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