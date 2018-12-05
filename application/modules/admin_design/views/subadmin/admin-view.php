<?php
$userPermission = isset($permission[1])?$permission[1]:array();
$versionPermission = isset($permission[2])?$permission[2]:array();
$notiPermission = isset($permission[3])?$permission[3]:array();
?>
<body>
    <!-- Content -->
    <section class="inner-right-panel clearfix">

        <!--breadcrumb wrap-->
        <div class="breadcrumb-wrap">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin/subadmin">Sub Admins</a></li>
                <li class="active">Sub-Admin Profile</li>
            </ol>
        </div>    
        <div class="clear"></div>

        <div class="section">
            <div class="row">
                <div class="col-sm-3 col-xs-12">
                    <!-- Thumb Wrapper -->
                    <div class="image-view-wrapper img-view200p img-viewbdr-radius4p img-mb">
                        <div class="profile-pic image-view img-view200" style="background-image:url('<?php echo (!empty($editdata['admin_profile_pic'])) ? IMAGE_PATH . $editdata['admin_profile_pic'] : DEFAULT_IMAGE ?>');"></div>
                    </div>
                    <!-- //Thumb Wrapper -->
                </div>
                <div class="col-sm-9 col-xs-12">
                    <div class="row">
                        <!-- form-->
                        <div class="user-detail-panel">
                            <div class="col-lg-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="admin-label">Name</label>
                                    <div class="input-holder">
                                        <span class="text-detail">Robert Lewandowski</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="admin-label">Mobile Number</label>
                                    <div class="input-holder">
                                        <span class="text-detail">91+ 9988787960</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="admin-label">Email ID</label>
                                    <div class="input-holder">
                                        <span class="text-detail">robert_lewandowsi@gmail.com</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="admin-label">Registered On</label>
                                    <div class="input-holder">
                                        <span class="text-detail">12-11-2018</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="admin-label">Access To</label>
                                    <div class="input-holder accessTo">
                                        <span class="text-detail">- User Management</span>
                                        <span class="text-detail">- Project Management</span>
                                        <span class="text-detail">- Content Management</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="admin-label">Password</label>
                                    <div class="input-holder">
                                        <span class="text-detail">xxxxxxxxxxxx</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- //form-->
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="button-wrap text-center">
                        <button type="button" class="commn-btn cancel" onclick="window.location.href = '<?php echo base_url() ?>admin/subadmin/edit'">Edit Profile</button>
                        <button type="submit" class="commn-btn save">Block Sub-Admin</button>
                    </div>
                </div>
            </div>
        </div>

    </section>
</body>
