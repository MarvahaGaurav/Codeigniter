<link href="public/css/form-roles.css" rel='stylesheet'>

<?php
$userPermission = isset($permission[1])?$permission[1]:array();
$versionPermission = isset($permission[2])?$permission[2]:array();
$notiPermission = isset($permission[3])?$permission[3]:array();
?>
<body>

    <!-- Content -->
    <section class="inner-right-panel clearfix">

        <div class="breadcrumb-wrap">
            <ol class="breadcrumb">
                <li><a href="admin/subadmin">Sub Admins</a></li>
                <li class="active">Update Sub-admin</li>
            </ol>
        </div>
        <div class="clear"></div>

        <div class="section">
            <div class="row">
                <div class="user-detail-panel">
                    <div class="col-sm-12">
                        <div class="form-item-title clearfix">
                            <h3 class="title">Sub-admin detail</h3>
                        </div>
                        <!-- title and form upper action end-->
                        <div class="row">
                            <div class="user-detail-panel">
                                <div class="col-sm-6 col-xs-6">
                                    <div class="form-group">
                                        <label class="admin-label">Admin Name</label>
                                        <div class="input-holder">
                                            <label><?php echo $admindetail['admin_name'] ?></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-xs-6">
                                    <div class="form-group">
                                        <label class="admin-label">Email</label>
                                        <div class="input-holder">
                                            <label><?php echo $admindetail['admin_email'] ?></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-xs-6">
                                    <div class="form-group">
                                        <label class="admin-label">Status</label>
                                        <div class="commn-select-wrap">
                                            <label><?php echo ($admindetail['status'] == 1)?'Active':'Blocked' ?></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div class="clearfix">
                                            <div class="row">
                                                <div class="col-lg-12"><h2 class="title-box m-t-n p-t-20">Sub-admin Roles :</h2></div>
                                                <div class="col-lg-12">
                                                    <div class="custom-check main-check">
                                                        <input id="main-check1" name="user" disabled="true"  value="1" <?php echo (!empty($userPermission))?'checked':'' ?> type="checkbox">
                                                        <label for="main-check1"><span></span>Manage User </label>
                                                        <ul class="check-column">
                                                            <li>
                                                                <div class="custom-check">
                                                                    <input id="subcheck1-1" <?php echo (!empty($userPermission['viewp']))?'checked':'' ?> disabled="true" value="1" class="user" type="checkbox" >
                                                                    <label for="subcheck1-1"><span></span>View </label>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="custom-check">
                                                                    <input id="subcheck1-2" <?php echo (!empty($userPermission['blockp']))?'checked':'' ?> value="1"  disabled="true" class="user" type="checkbox" >
                                                                    <label for="subcheck1-2"><span></span>Block  </label>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="custom-check">
                                                                    <input id="subcheck1-3" <?php echo (!empty($userPermission['deletep']))?'checked':'' ?> value="1" class="user" disabled="true" type="checkbox" >
                                                                    <label for="subcheck1-3"><span></span>Delete</label>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>			
                                                    <div class="clear"></div>
                                                    <div class="custom-check main-check">
                                                        <input id="main-check2" name="version" <?php echo (!empty($versionPermission))?'checked':'' ?> disabled="true" value="1" type="checkbox">
                                                        <label for="main-check2"><span></span>Manage Version</label>
                                                        <ul class="check-column">
                                                            <li>
                                                                <div class="custom-check">
                                                                    <input id="subcheck2-4" <?php echo (!empty($versionPermission['addp']))?'checked':'' ?> value="1"  disabled="true" class="Version" type="checkbox">
                                                                    <label for="subcheck2-4"><span></span>Add New</label>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="custom-check">
                                                                    <input id="subcheck2-2" <?php echo (!empty($versionPermission['editp']))?'checked':'' ?> value="1" disabled="true" class="Version" type="checkbox">
                                                                    <label for="subcheck2-2"><span></span>Edit </label>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="custom-check">
                                                                    <input id="subcheck2-3" <?php echo (!empty($versionPermission['deletep']))?'checked':'' ?> value="1" disabled="true" class="Version" type="checkbox">
                                                                    <label for="subcheck2-3"><span></span>Delete </label>
                                                                </div>
                                                            </li>

                                                        </ul>
                                                    </div>			
                                                    <div class="clear"></div>
                                                    <div class="custom-check main-check">
                                                        <input id="main-check3" name="portfolio"  <?php echo (!empty($notiPermission))?'checked':'' ?> value="3" disabled="true" type="checkbox">
                                                        <label for="main-check3"><span></span>Manage Notifications </label>
                                                        <ul class="check-column">
                                                            <li>
                                                                <div class="custom-check">
                                                                    <input id="subcheck3-1" disabled="true" <?php echo (!empty($notiPermission['addp']))?'checked':'' ?> value="1"  class="Notification"  type="checkbox">
                                                                    <label for="subcheck3-1"><span></span>Add </label>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="custom-check">
                                                                    <input id="subcheck3-2" disabled="true" <?php echo (!empty($notiPermission['editp']))?'checked':'' ?> value="1" class="Notification"  type="checkbox">
                                                                    <label for="subcheck3-2"><span></span>Edit or Resend</label>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="custom-check">
                                                                    <input id="subcheck3-4" disabled="true"  <?php echo (!empty($notiPermission['deletep']))?'checked':'' ?> value="1" class="Notification"  type="checkbox">
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
                    </div>
                </div>
            </div>
        </div>

    </section>
</body>