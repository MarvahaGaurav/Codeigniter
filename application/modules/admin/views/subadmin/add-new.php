<body>
    <!-- Content -->
    <section class="content-wrapper clearfix">
        <div class="upper-head-panel m-b-lg clearfix">
            <ul class="breadcrumb reward-breadcrumb">
                <li><a href="admin/subadmin">Sub Admins</a></li>
                <li class="active">Add Sub-admin</li>
            </ul>
        </div>	
        <div class="clear"></div>
        <div class="col-sm-12">
            <div class="adminRoles-wrapper">
            <div class="form-item-title clearfix">
                <h3 class="title">Fill the below form</h3>
            </div>
            <!-- title and form upper action end-->
            <?php echo form_open_multipart('',array('id'=>'subadmin_add'));?>
            <div class="form-ele-wrapper clearfix">
                <div class="row">
                    <div class="col-sm-6 col-xs-6">
                        <div class="form-group">
                            <label class="admin-label">Admin Name</label>
                            <div class="input-holder">
                                <input type="text" class="form-control material-control" maxlength="30" name="name" placeholder="* Sub-admin Name" value="<?php echo set_value('name'); ?>">
                                <?php echo form_error('name', '<label class="alert-danger">', '</label>'); ?>   
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-6">
                        <div class="form-group">
                            <label class="admin-label">Email</label>
                            <div class="input-holder">
                                <input type="text" class="form-control material-control" maxlength="30" name="email" placeholder="* Sub-admin Email" value="<?php echo set_value('email'); ?>">
                                <?php echo form_error('email', '<label class=" alert-danger">', '</label>'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-6">
                        <div class="form-group">
                            <label class="admin-label">Password</label>
                            <div class="input-holder">
                                <input type="text" class="form-control material-control" maxlength="16" name="password" placeholder="* Sub-admin Password" value="<?php echo set_value('password'); ?>">
                                <?php echo form_error('password', '<label class=" alert-danger">', '</label>'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-6">
                        <label class="admin-label">Status</label>
                        <div class="commn-select-wrap">
                            <select class="selectpicker" name="status">
                                <option value="">Select</option>
                                <option value="1">Active</option>
                                <option value="2">Inactive</option>
                            </select>
                            <?php echo form_error('status', '<label class="alert-danger">', '</label>'); ?>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="adminRoles-wrapper p-md m-b-lg">
                            <div class="form-ele-wrapper clearfix">
                                <div class="row">
                                    <div class="col-lg-12"><h2 class="title-box m-t-n p-t-20">Sub-admin Roles :</h2></div>
                                    <div class="col-lg-12">
                                        <div class="custom-check main-check">
                                            <input id="main-check1" name="user" onchange="permission('user')"  value="1"   type="checkbox">
                                            <label for="main-check1"><span></span>Manage User </label>
                                            <ul class="check-column">
                                                <li>
                                                    <div class="custom-check">
                                                        <input id="subcheck1-1" disabled="true" name="permission[user][view]" value="1" class="user" type="checkbox">
                                                        <label for="subcheck1-1"><span></span>View </label>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="custom-check">
                                                        <input id="subcheck1-2" disabled="true" name="permission[user][block]" value="1"  class="user" type="checkbox">
                                                        <label for="subcheck1-2"><span></span>Block  </label>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="custom-check">
                                                        <input id="subcheck1-3" disabled="true" name="permission[user][delete]" value="1" class="user" type="checkbox">
                                                        <label for="subcheck1-3"><span></span>Delete</label>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>			
                                        <div class="clear"></div>
                                        <div class="custom-check main-check">
                                            <input id="main-check2" name="option" onchange="permission('Version')" value="2" type="checkbox">
                                            <label for="main-check2"><span></span>Manage Version</label>
                                            <ul class="check-column">
                                                <li>
                                                    <div class="custom-check">
                                                        <input id="subcheck2-4" disabled="true" name="permission[version][add]" value="1"  class="Version" type="checkbox">
                                                        <label for="subcheck2-4"><span></span>Add</label>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="custom-check">
                                                        <input id="subcheck2-2" disabled="true" name="permission[version][edit]" value="1" class="Version" type="checkbox">
                                                        <label for="subcheck2-2"><span></span>Edit </label>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="custom-check">
                                                        <input id="subcheck2-3" disabled="true" name="permission[version][delete]" value="1" class="Version" type="checkbox">
                                                        <label for="subcheck2-3"><span></span>Delete </label>
                                                    </div>
                                                </li>

                                            </ul>
                                        </div>			
                                        <div class="clear"></div>
                                        <div class="custom-check main-check">
                                            <input id="main-check3" name="portfolio" onchange="permission('Notification')" value="3"  type="checkbox">
                                            <label for="main-check3"><span></span>Manage Notifications </label>
                                            <ul class="check-column">
                                                <li>
                                                    <div class="custom-check">
                                                        <input id="subcheck3-1" disabled="true" name="permission[notification][add]" value="1"class="Notification"  type="checkbox">
                                                        <label for="subcheck3-1"><span></span>Add </label>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="custom-check">
                                                        <input id="subcheck3-3" disabled="true" name="permission[notification][edit]" value="1" class="Notification"  type="checkbox">
                                                        <label for="subcheck3-3"><span></span>Edit or Resend</label>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="custom-check">
                                                        <input id="subcheck3-4" disabled="true" name="permission[notification][delete]" value="1" class="Notification"  type="checkbox">
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
                    <button type="button"  onclick="window.location.href = '<?php echo base_url() ?>admin/version'"class="commn-btn cancel">Cancel</button>
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