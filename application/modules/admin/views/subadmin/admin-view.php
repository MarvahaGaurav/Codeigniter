<link href="public/css/form-roles.css" rel='stylesheet'>

<?php
$userPermission = isset($permission[1])?$permission[1]:array();
$merchantPermission = isset($permission[2])?$permission[2]:array();
$productPermission = isset($permission[3])?$permission[3]:array();
$templatePermission = isset($permission[4])?$permission[4]:array();
$contentPermission = isset($permission[5])?$permission[5]:array();
$versionPermission = isset($permission[6])?$permission[6]:array();
$notiPermission = isset($permission[7])?$permission[7]:array();
$messagePermission = isset($permission[8])?$permission[8]:array();
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
                            
                        </div>
                        <div class="row">
                            <div class="user-detail-panel">
                                <div class="col-lg-12 col-sm-12 col-xs-12">
                                    <!-- Thumb Wrapper -->
                                    <div class="image-view-wrapper img-view200p img-viewbdr-radius4p img-mb">
                                        <div class="profile-pic image-view img-view200" style="background-image:url('<?php echo (!empty($admindetail['admin_profile_pic'])) ? $admindetail['admin_profile_pic'] : DEFAULT_IMAGE ?>');"></div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-xs-6">
                                    <div class="form-group">
                                        <label class="admin-label">Admin Name</label>
                                        <div class="input-holder text-detail">
                                            <?php echo $admindetail['admin_name'] ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-xs-6">
                                    <div class="form-group">
                                        <label class="admin-label">Email</label>
                                        <div class="input-holder text-detail">
                                            <?php echo $admindetail['admin_email'] ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-xs-6">
                                    <div class="form-group">
                                        <label class="admin-label">Status</label>
                                        <div class="commn-select-wrap text-detail">
                                            <?php echo ($admindetail['status'] == 1)?'Active':'Blocked' ?>
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
                                                                    <input id="subcheck1-2" <?php echo (!empty($userPermission['addp']))?'checked':'' ?> disabled="true" value="1" class="user" type="checkbox" >
                                                                    <label for="subcheck1-2"><span></span>Add </label>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="custom-check">
                                                                    <input id="subcheck1-3" <?php echo (!empty($userPermission['editp']))?'checked':'' ?> value="1"  disabled="true" class="user" type="checkbox" >
                                                                    <label for="subcheck1-3"><span></span>Edit  </label>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="custom-check">
                                                                    <input id="subcheck1-4" <?php echo (!empty($userPermission['blockp']))?'checked':'' ?> value="1" class="user" disabled="true" type="checkbox" >
                                                                    <label for="subcheck1-4"><span></span>Block</label>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="custom-check">
                                                                    <input id="subcheck1-5" <?php echo (!empty($userPermission['deletep']))?'checked':'' ?> value="1" class="user" disabled="true" type="checkbox" >
                                                                    <label for="subcheck1-5"><span></span>Delete</label>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>            
                                                    <div class="clear"></div>
                                                    <div class="custom-check main-check">
                                                        <input id="main-check2" name="merchant" disabled="true"  value="1" <?php echo (!empty($merchantPermission))?'checked':'' ?> type="checkbox">
                                                        <label for="main-check2"><span></span>Manage Merchant </label>
                                                        <ul class="check-column">
                                                            <li>
                                                                <div class="custom-check">
                                                                    <input id="subcheck2-1" <?php echo (!empty($merchantPermission['viewp']))?'checked':'' ?> disabled="true" value="1" class="merchant" type="checkbox" >
                                                                    <label for="subcheck2-1"><span></span>View </label>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="custom-check">
                                                                    <input id="subcheck2-2" <?php echo (!empty($merchantPermission['addp']))?'checked':'' ?> disabled="true" value="1" class="merchant" type="checkbox" >
                                                                    <label for="subcheck2-2"><span></span>Add </label>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="custom-check">
                                                                    <input id="subcheck2-3" <?php echo (!empty($merchantPermission['editp']))?'checked':'' ?> value="1"  disabled="true" class="merchant" type="checkbox" >
                                                                    <label for="subcheck2-3"><span></span>Edit  </label>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="custom-check">
                                                                    <input id="subcheck2-4" <?php echo (!empty($merchantPermission['blockp']))?'checked':'' ?> value="1" class="merchant" disabled="true" type="checkbox" >
                                                                    <label for="subcheck2-4"><span></span>Block</label>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="custom-check">
                                                                    <input id="subcheck2-5" <?php echo (!empty($merchantPermission['deletep']))?'checked':'' ?> value="1" class="merchant" disabled="true" type="checkbox" >
                                                                    <label for="subcheck2-5"><span></span>Delete</label>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>            
                                                    <div class="clear"></div>
                                                    <div class="custom-check main-check">
                                                        <input id="main-check3" name="product" disabled="true"  value="1" <?php echo (!empty($productPermission))?'checked':'' ?> type="checkbox">
                                                        <label for="main-check3"><span></span>Manage product </label>
                                                        <ul class="check-column">
                                                            <li>
                                                                <div class="custom-check">
                                                                    <input id="subcheck3-1" <?php echo (!empty($productPermission['viewp']))?'checked':'' ?> disabled="true" value="1" class="product" type="checkbox" >
                                                                    <label for="subcheck3-1"><span></span>View </label>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="custom-check">
                                                                    <input id="subcheck3-2" <?php echo (!empty($productPermission['addp']))?'checked':'' ?> disabled="true" value="1" class="product" type="checkbox" >
                                                                    <label for="subcheck3-2"><span></span>Add </label>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="custom-check">
                                                                    <input id="subcheck3-3" <?php echo (!empty($productPermission['editp']))?'checked':'' ?> value="1"  disabled="true" class="product" type="checkbox" >
                                                                    <label for="subcheck3-3"><span></span>Edit  </label>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="custom-check">
                                                                    <input id="subcheck3-4" <?php echo (!empty($productPermission['blockp']))?'checked':'' ?> value="1" class="product" disabled="true" type="checkbox" >
                                                                    <label for="subcheck3-4"><span></span>Block</label>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="custom-check">
                                                                    <input id="subcheck3-5" <?php echo (!empty($productPermission['deletep']))?'checked':'' ?> value="1" class="product" disabled="true" type="checkbox" >
                                                                    <label for="subcheck3-5"><span></span>Delete</label>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>            
                                                    <div class="clear"></div>
                                                    <div class="custom-check main-check">
                                                        <input id="main-check4" name="template" disabled="true"  value="1" <?php echo (!empty($templatePermission))?'checked':'' ?> type="checkbox">
                                                        <label for="main-check4"><span></span>Manage Template </label>
                                                        <ul class="check-column">
                                                            <li>
                                                                <div class="custom-check">
                                                                    <input id="subcheck4-1" <?php echo (!empty($templatePermission['viewp']))?'checked':'' ?> disabled="true" value="1" class="template" type="checkbox" >
                                                                    <label for="subcheck4-1"><span></span>View </label>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="custom-check">
                                                                    <input id="subcheck4-2" <?php echo (!empty($templatePermission['addp']))?'checked':'' ?> disabled="true" value="1" class="template" type="checkbox" >
                                                                    <label for="subcheck4-2"><span></span>Add </label>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="custom-check">
                                                                    <input id="subcheck4-3" <?php echo (!empty($templatePermission['editp']))?'checked':'' ?> value="1"  disabled="true" class="template" type="checkbox" >
                                                                    <label for="subcheck4-3"><span></span>Edit  </label>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="custom-check">
                                                                    <input id="subcheck4-4" <?php echo (!empty($templatePermission['blockp']))?'checked':'' ?> value="1" class="template" disabled="true" type="checkbox" >
                                                                    <label for="subcheck4-4"><span></span>Block</label>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="custom-check">
                                                                    <input id="subcheck4-5" <?php echo (!empty($templatePermission['deletep']))?'checked':'' ?> value="1" class="merchant" disabled="true" type="checkbox" >
                                                                    <label for="subcheck4-5"><span></span>Delete</label>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>            
                                                    <div class="clear"></div>
                                                    <div class="custom-check main-check">
                                                        <input id="main-check5" name="content" disabled="true"  value="1" <?php echo (!empty($contentPermission))?'checked':'' ?> type="checkbox">
                                                        <label for="main-check5"><span></span>Manage Content </label>
                                                        <ul class="check-column">
                                                            <li>
                                                                <div class="custom-check">
                                                                    <input id="subcheck5-1" <?php echo (!empty($contentPermission['viewp']))?'checked':'' ?> disabled="true" value="1" class="content" type="checkbox" >
                                                                    <label for="subcheck5-1"><span></span>View </label>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="custom-check">
                                                                    <input id="subcheck5-2" <?php echo (!empty($contentPermission['addp']))?'checked':'' ?> disabled="true" value="1" class="content" type="checkbox" >
                                                                    <label for="subcheck5-2"><span></span>Add </label>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="custom-check">
                                                                    <input id="subcheck5-3" <?php echo (!empty($contentPermission['editp']))?'checked':'' ?> value="1"  disabled="true" class="content" type="checkbox" >
                                                                    <label for="subcheck5-3"><span></span>Edit  </label>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="custom-check">
                                                                    <input id="subcheck5-4" <?php echo (!empty($contentPermission['blockp']))?'checked':'' ?> value="1" class="content" disabled="true" type="checkbox" >
                                                                    <label for="subcheck5-4"><span></span>Block</label>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="custom-check">
                                                                    <input id="subcheck5-5" <?php echo (!empty($contentPermission['deletep']))?'checked':'' ?> value="1" class="content" disabled="true" type="checkbox" >
                                                                    <label for="subcheck5-5"><span></span>Delete</label>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>            
                                                    <div class="clear"></div>
                                                    <div class="custom-check main-check">
                                                        <input id="main-check6" name="version" disabled="true"  value="1" <?php echo (!empty($versionPermission))?'checked':'' ?> type="checkbox">
                                                        <label for="main-check6"><span></span>Manage Version </label>
                                                        <ul class="check-column">
                                                            <li>
                                                                <div class="custom-check">
                                                                    <input id="subcheck6-1" <?php echo (!empty($versionPermission['viewp']))?'checked':'' ?> disabled="true" value="1" class="version" type="checkbox" >
                                                                    <label for="subcheck6-1"><span></span>View </label>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="custom-check">
                                                                    <input id="subcheck6-2" <?php echo (!empty($versionPermission['addp']))?'checked':'' ?> disabled="true" value="1" class="version" type="checkbox" >
                                                                    <label for="subcheck6-2"><span></span>Add </label>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="custom-check">
                                                                    <input id="subcheck6-3" <?php echo (!empty($versionPermission['editp']))?'checked':'' ?> value="1"  disabled="true" class="version" type="checkbox" >
                                                                    <label for="subcheck6-3"><span></span>Edit  </label>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="custom-check">
                                                                    <input id="subcheck6-4" <?php echo (!empty($versionPermission['blockp']))?'checked':'' ?> value="1" class="version" disabled="true" type="checkbox" >
                                                                    <label for="subcheck6-4"><span></span>Block</label>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="custom-check">
                                                                    <input id="subcheck6-5" <?php echo (!empty($versionPermission['deletep']))?'checked':'' ?> value="1" class="version" disabled="true" type="checkbox" >
                                                                    <label for="subcheck6-5"><span></span>Delete</label>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>            
                                                    <div class="clear"></div>
                                                    <div class="custom-check main-check">
                                                        <input id="main-check7" name="noti" disabled="true"  value="1" <?php echo (!empty($notiPermission))?'checked':'' ?> type="checkbox">
                                                        <label for="main-check7"><span></span>Manage Notification </label>
                                                        <ul class="check-column">
                                                            <li>
                                                                <div class="custom-check">
                                                                    <input id="subcheck7-1" <?php echo (!empty($notiPermission['viewp']))?'checked':'' ?> disabled="true" value="1" class="noti" type="checkbox" >
                                                                    <label for="subcheck7-1"><span></span>View </label>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="custom-check">
                                                                    <input id="subcheck7-2" <?php echo (!empty($notiPermission['addp']))?'checked':'' ?> disabled="true" value="1" class="noti" type="checkbox" >
                                                                    <label for="subcheck7-2"><span></span>Add </label>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="custom-check">
                                                                    <input id="subcheck7-3" <?php echo (!empty($notiPermission['editp']))?'checked':'' ?> value="1"  disabled="true" class="noti" type="checkbox" >
                                                                    <label for="subcheck7-3"><span></span>Edit or Resend </label>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="custom-check">
                                                                    <input id="subcheck7-4" <?php echo (!empty($notiPermission['blockp']))?'checked':'' ?> value="1" class="noti" disabled="true" type="checkbox" >
                                                                    <label for="subcheck7-4"><span></span>Block</label>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="custom-check">
                                                                    <input id="subcheck7-5" <?php echo (!empty($notiPermission['deletep']))?'checked':'' ?> value="1" class="noti" disabled="true" type="checkbox" >
                                                                    <label for="subcheck7-5"><span></span>Delete</label>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>            
                                                    <div class="clear"></div>
                                                    <div class="custom-check main-check">
                                                        <input id="main-check8" name="message" disabled="true"  value="1" <?php echo (!empty($messagePermission))?'checked':'' ?> type="checkbox">
                                                        <label for="main-check8"><span></span>Manage Messages </label>
                                                        <ul class="check-column">
                                                            <li>
                                                                <div class="custom-check">
                                                                    <input id="subcheck8-1" <?php echo (!empty($messagePermission['viewp']))?'checked':'' ?> disabled="true" value="1" class="message" type="checkbox" >
                                                                    <label for="subcheck8-1"><span></span>View </label>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="custom-check">
                                                                    <input id="subcheck8-2" <?php echo (!empty($messagePermission['addp']))?'checked':'' ?> disabled="true" value="1" class="message" type="checkbox" >
                                                                    <label for="subcheck8-2"><span></span>Add </label>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="custom-check">
                                                                    <input id="subcheck8-3" <?php echo (!empty($messagePermission['editp']))?'checked':'' ?> value="1"  disabled="true" class="message" type="checkbox" >
                                                                    <label for="subcheck8-3"><span></span>Edit  </label>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="custom-check">
                                                                    <input id="subcheck8-4" <?php echo (!empty($messagePermission['blockp']))?'checked':'' ?> value="1" class="message" disabled="true" type="checkbox" >
                                                                    <label for="subcheck8-4"><span></span>Block</label>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="custom-check">
                                                                    <input id="subcheck8-5" <?php echo (!empty($messagePermission['deletep']))?'checked':'' ?> value="1" class="message" disabled="true" type="checkbox" >
                                                                    <label for="subcheck8-5"><span></span>Delete</label>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>            
                                                    <div class="clear"></div>
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