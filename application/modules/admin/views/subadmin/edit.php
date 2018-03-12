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
                <div class="col-sm-12">
                    <div class="form-item-title clearfix">
                        <h3 class="title">Update the Sub-admin detail</h3>
                    </div>
                    <!-- title and form upper action end-->
                    <?php echo form_open_multipart('', array('id' => 'subadmin_add')); ?>
                    <br><br>
                    <div class="row">
                        <div class="col-lg-3 col-sm-4">
                            <label class="admin-label">Profile Picture<br></label>
                            <div class="image-view-wrapper img-view200p img-viewbdr-radius4p">
                                <div class="image-view img-view200">
                                    <div class="photo-upload-here">
                                        <img style="width: 100%;height: 100%;" class="profile-pic" id="profile_image" src="<?php echo (!empty($admindetail['admin_profile_pic']))?$admindetail['admin_profile_pic']:DEFAULT_IMAGE ?>">
                                    </div>
                                    
                                    <div class="image_upload_trigger" onclick="addCoverImage()">
                                        <a href="javascript:void(0);" class="upimage-btn">
                                        </a>
                                        <label class="camera" for="upload"><i class="fa fa-camera" aria-hidden="true"></i></label>
                                        <input type="hidden" name="imgurl" class="inputhidden">
                                        <input type="hidden" id="imgChange" name="imgChange" value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type='hidden' value='<?php echo encryptDecrypt($admin_id); ?>' name='token' >
                    <div class="form-ele-wrapper clearfix">
                    <div class="row">
                        <div class="user-detail-panel">
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="admin-label">Admin Name</label>
                                    <div class="input-holder">
                                        <input type="text" class="" maxlength="30" name="name" placeholder="* Sub-admin Name" value="<?php echo $admindetail['admin_name']; ?>">
                                        <?php echo form_error('name', '<label class="alert-danger">', '</label>'); ?>   
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="admin-label">Email</label>
                                    <div class="input-holder">
                                        <input type="text" class="" maxlength="50" name="email" placeholder="* Sub-admin Email" readonly="readonly"value="<?php echo $admindetail['admin_email']; ?>">
                                        <?php echo form_error('email', '<label class=" alert-danger">', '</label>'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <label class="admin-label">Status</label>
                                <div class="commn-select-wrap">
                                    <select class="selectpicker" name="status">
                                        <option <?php if($admindetail['status'] == 0){echo 'selected';} ?> value="0">Inactive</option>
                                        <option <?php if($admindetail['status'] == 1){echo 'selected';} ?> value="1">Active</option>
                                        <option <?php if($admindetail['status'] == 2){echo 'selected';} ?> value="2">Block</option>
                                    </select>
                                    <?php echo form_error('status', '<label class="alert-danger">', '</label>'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12"><h2 class="title-box m-t-n p-t-20">Sub-admin Roles :</h2></div>
                        <div class="col-lg-12" id="allcheckboxes">
                            <div class="custom-check main-check">
                                <input id="main-check1" onchange="permission('user', 'main-check1')" name="user"  value="1" <?php echo (!empty($userPermission))?'checked':'' ?> type="checkbox">
                                <label for="main-check1"><span></span>Manage User </label>
                                <ul class="check-column">
                                    <li>
                                        <div class="custom-check">
                                            <input id="subcheck1-1" name="permission[user][view]" <?php echo (!empty($userPermission['viewp']))?'checked':'' ?> value="1" class="user" type="checkbox" >
                                            <label for="subcheck1-1"><span></span>View </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="custom-check">
                                            <input id="subcheck1-2" name="permission[user][add]" <?php echo (!empty($userPermission['addp']))?'checked':'' ?> value="1" class="user" type="checkbox" >
                                            <label for="subcheck1-2"><span></span>Add </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="custom-check">
                                            <input id="subcheck1-3" name="permission[user][edit]" <?php echo (!empty($userPermission['editp']))?'checked':'' ?> value="1"  class="user" type="checkbox" >
                                            <label for="subcheck1-3"><span></span>Edit  </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="custom-check">
                                            <input id="subcheck1-4" name="permission[user][block]" <?php echo (!empty($userPermission['blockp']))?'checked':'' ?> value="1" class="user" type="checkbox" >
                                            <label for="subcheck1-4"><span></span>Block</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="custom-check">
                                            <input id="subcheck1-5" name="permission[user][delete]" <?php echo (!empty($userPermission['deletep']))?'checked':'' ?> value="1" class="user" type="checkbox" >
                                            <label for="subcheck1-5"><span></span>Delete</label>
                                        </div>
                                    </li>
                                </ul>
                            </div>			
                            <div class="clear"></div>
                            <div class="custom-check main-check">
                                <input id="main-check2" onchange="permission('merchant', 'main-check2')" name="merchant"  value="2" <?php echo (!empty($merchantPermission))?'checked':'' ?> type="checkbox">
                                <label for="main-check2"><span></span>Manage Merchant </label>
                                <ul class="check-column">
                                    <li>
                                        <div class="custom-check">
                                            <input id="subcheck2-1" name="permission[merchant][view]" <?php echo (!empty($merchantPermission['viewp']))?'checked':'' ?> value="1" class="merchant" type="checkbox" >
                                            <label for="subcheck2-1"><span></span>View </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="custom-check">
                                            <input id="subcheck2-2" name="permission[merchant][add]" <?php echo (!empty($merchantPermission['addp']))?'checked':'' ?> value="1" class="merchant" type="checkbox" >
                                            <label for="subcheck2-2"><span></span>Add </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="custom-check">
                                            <input id="subcheck2-3" name="permission[merchant][edit]" <?php echo (!empty($merchantPermission['editp']))?'checked':'' ?> value="1"  class="merchant" type="checkbox" >
                                            <label for="subcheck2-3"><span></span>Edit  </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="custom-check">
                                            <input id="subcheck2-4" name="permission[merchant][block]" <?php echo (!empty($merchantPermission['blockp']))?'checked':'' ?> value="1" class="merchant" type="checkbox" >
                                            <label for="subcheck2-4"><span></span>Block</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="custom-check">
                                            <input id="subcheck2-5" name="permission[merchant][delete]" <?php echo (!empty($merchantPermission['deletep']))?'checked':'' ?> value="1" class="merchant" type="checkbox" >
                                            <label for="subcheck2-5"><span></span>Delete</label>
                                        </div>
                                    </li>
                                </ul>
                            </div>			
                            <div class="clear"></div>
                            <div class="custom-check main-check">
                                <input id="main-check3" onchange="permission('product', 'main-check3')" name="product"  value="3" <?php echo (!empty($productPermission))?'checked':'' ?> type="checkbox">
                                <label for="main-check3"><span></span>Manage product </label>
                                <ul class="check-column">
                                    <li>
                                        <div class="custom-check">
                                            <input id="subcheck3-1" name="permission[product][view]" <?php echo (!empty($productPermission['viewp']))?'checked':'' ?> value="1" class="product" type="checkbox" >
                                            <label for="subcheck3-1"><span></span>View </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="custom-check">
                                            <input id="subcheck3-2" name="permission[product][add]" <?php echo (!empty($productPermission['addp']))?'checked':'' ?> value="1" class="product" type="checkbox" >
                                            <label for="subcheck3-2"><span></span>Add </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="custom-check">
                                            <input id="subcheck3-3" name="permission[product][edit]" <?php echo (!empty($productPermission['editp']))?'checked':'' ?> value="1"  class="product" type="checkbox" >
                                            <label for="subcheck3-3"><span></span>Edit  </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="custom-check">
                                            <input id="subcheck3-4" name="permission[product][block]" <?php echo (!empty($productPermission['blockp']))?'checked':'' ?> value="1" class="product" type="checkbox" >
                                            <label for="subcheck3-4"><span></span>Block</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="custom-check">
                                            <input id="subcheck3-5" name="permission[product][delete]" <?php echo (!empty($productPermission['deletep']))?'checked':'' ?> value="1" class="product" type="checkbox" >
                                            <label for="subcheck3-5"><span></span>Delete</label>
                                        </div>
                                    </li>
                                </ul>
                            </div>			
                            <div class="clear"></div>
                            <div class="custom-check main-check">
                                <input id="main-check4" onchange="permission('template', 'main-check4')" name="template"  value="4" <?php echo (!empty($templatePermission))?'checked':'' ?> type="checkbox">
                                <label for="main-check4"><span></span>Manage Template </label>
                                <ul class="check-column">
                                    <li>
                                        <div class="custom-check">
                                            <input id="subcheck4-1" name="permission[template][view]" <?php echo (!empty($templatePermission['viewp']))?'checked':'' ?>  value="1" class="template" type="checkbox" >
                                            <label for="subcheck4-1"><span></span>View </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="custom-check">
                                            <input id="subcheck4-2" name="permission[template][add]" <?php echo (!empty($templatePermission['addp']))?'checked':'' ?> value="1" class="template" type="checkbox" >
                                            <label for="subcheck4-2"><span></span>Add </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="custom-check">
                                            <input id="subcheck4-3" name="permission[template][edit]" <?php echo (!empty($templatePermission['editp']))?'checked':'' ?> value="1"   class="template" type="checkbox" >
                                            <label for="subcheck4-3"><span></span>Edit  </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="custom-check">
                                            <input id="subcheck4-4" name="permission[template][block]" <?php echo (!empty($templatePermission['blockp']))?'checked':'' ?> value="1" class="template"  type="checkbox" >
                                            <label for="subcheck4-4"><span></span>Block</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="custom-check">
                                            <input id="subcheck4-5" name="permission[template][delete]" <?php echo (!empty($templatePermission['deletep']))?'checked':'' ?> value="1" class="template"  type="checkbox" >
                                            <label for="subcheck4-5"><span></span>Delete</label>
                                        </div>
                                    </li>
                                </ul>
                            </div>			
                            <div class="clear"></div>
                            <div class="custom-check main-check">
                                <input id="main-check5" onchange="permission('content', 'main-check5')" name="content"  value="5" <?php echo (!empty($contentPermission))?'checked':'' ?> type="checkbox">
                                <label for="main-check5"><span></span>Manage Content </label>
                                <ul class="check-column">
                                    <li>
                                        <div class="custom-check">
                                            <input id="subcheck5-1" name="permission[content][view]" <?php echo (!empty($contentPermission['viewp']))?'checked':'' ?>  value="1" class="content" type="checkbox" >
                                            <label for="subcheck5-1"><span></span>View </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="custom-check">
                                            <input id="subcheck5-2" name="permission[content][add]" <?php echo (!empty($contentPermission['addp']))?'checked':'' ?>  value="1" class="content" type="checkbox" >
                                            <label for="subcheck5-2"><span></span>Add </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="custom-check">
                                            <input id="subcheck5-3" name="permission[content][edit]" <?php echo (!empty($contentPermission['editp']))?'checked':'' ?> value="1"  class="content" type="checkbox" >
                                            <label for="subcheck5-3"><span></span>Edit  </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="custom-check">
                                            <input id="subcheck5-4" name="permission[content][block]" <?php echo (!empty($contentPermission['blockp']))?'checked':'' ?> value="1" class="content" type="checkbox" >
                                            <label for="subcheck5-4"><span></span>Block</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="custom-check">
                                            <input id="subcheck5-5" name="permission[content][delete]" <?php echo (!empty($contentPermission['deletep']))?'checked':'' ?> value="1" class="content" type="checkbox" >
                                            <label for="subcheck5-5"><span></span>Delete</label>
                                        </div>
                                    </li>
                                </ul>
                            </div>			
                            <div class="clear"></div>
                            <div class="custom-check main-check">
                                <input id="main-check6" onchange="permission('version', 'main-check6')" name="version" value="6" <?php echo (!empty($versionPermission))?'checked':'' ?> type="checkbox">
                                <label for="main-check6"><span></span>Manage Version </label>
                                <ul class="check-column">
                                    <li>
                                        <div class="custom-check">
                                            <input id="subcheck6-1" name="permission[version][view]" <?php echo (!empty($versionPermission['viewp']))?'checked':'' ?> value="1" class="version" type="checkbox" >
                                            <label for="subcheck6-1"><span></span>View </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="custom-check">
                                            <input id="subcheck6-2" name="permission[version][add]" <?php echo (!empty($versionPermission['addp']))?'checked':'' ?> value="1" class="version" type="checkbox" >
                                            <label for="subcheck6-2"><span></span>Add </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="custom-check">
                                            <input id="subcheck6-3" name="permission[version][edit]" <?php echo (!empty($versionPermission['editp']))?'checked':'' ?> value="1" class="version" type="checkbox" >
                                            <label for="subcheck6-3"><span></span>Edit  </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="custom-check">
                                            <input id="subcheck6-4" name="permission[version][block]" <?php echo (!empty($versionPermission['blockp']))?'checked':'' ?> value="1" class="version" type="checkbox" >
                                            <label for="subcheck6-4"><span></span>Block</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="custom-check">
                                            <input id="subcheck6-5" name="permission[version][delete]" <?php echo (!empty($versionPermission['deletep']))?'checked':'' ?> value="1" class="version" type="checkbox" >
                                            <label for="subcheck6-5"><span></span>Delete</label>
                                        </div>
                                    </li>
                                </ul>
                            </div>			
                            <div class="clear"></div>
                            <div class="custom-check main-check">
                                <input id="main-check7" onchange="permission('noti', 'main-check7')" name="noti"   value="7" <?php echo (!empty($notiPermission))?'checked':'' ?> type="checkbox">
                                <label for="main-check7"><span></span>Manage Notification </label>
                                <ul class="check-column">
                                    <li>
                                        <div class="custom-check">
                                            <input id="subcheck7-1" name="permission[notification][view]" <?php echo (!empty($notiPermission['viewp']))?'checked':'' ?>  value="1" class="noti" type="checkbox" >
                                            <label for="subcheck7-1"><span></span>View </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="custom-check">
                                            <input id="subcheck7-2" name="permission[notification][add]" <?php echo (!empty($notiPermission['addp']))?'checked':'' ?> value="1" class="noti" type="checkbox" >
                                            <label for="subcheck7-2"><span></span>Add </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="custom-check">
                                            <input id="subcheck7-3" name="permission[notification][edit]" <?php echo (!empty($notiPermission['editp']))?'checked':'' ?> value="1"  class="noti" type="checkbox" >
                                            <label for="subcheck7-3"><span></span>Edit or Resend </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="custom-check">
                                            <input id="subcheck7-4" name="permission[notification][block]" <?php echo (!empty($notiPermission['blockp']))?'checked':'' ?> value="1" class="noti" type="checkbox" >
                                            <label for="subcheck7-4"><span></span>Block</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="custom-check">
                                            <input id="subcheck7-5" name="permission[notification][delete]" <?php echo (!empty($notiPermission['deletep']))?'checked':'' ?> value="1" class="noti" type="checkbox" >
                                            <label for="subcheck7-5"><span></span>Delete</label>
                                        </div>
                                    </li>
                                </ul>
                            </div>			
                            <div class="clear"></div>
                            <div class="custom-check main-check">
                                <input id="main-check8" onchange="permission('message', 'main-check8')" name="message" value="8" <?php echo (!empty($messagePermission))?'checked':'' ?> type="checkbox">
                                <label for="main-check8"><span></span>Manage Messages </label>
                                <ul class="check-column">
                                    <li>
                                        <div class="custom-check">
                                            <input id="subcheck8-1" name="permission[messages][view]" <?php echo (!empty($messagePermission['viewp']))?'checked':'' ?>  value="1" class="message" type="checkbox" >
                                            <label for="subcheck8-1"><span></span>View </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="custom-check">
                                            <input id="subcheck8-2" name="permission[messages][add]" <?php echo (!empty($messagePermission['addp']))?'checked':'' ?>  value="1" class="message" type="checkbox" >
                                            <label for="subcheck8-2"><span></span>Add </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="custom-check">
                                            <input id="subcheck8-3" name="permission[messages][edit]" <?php echo (!empty($messagePermission['editp']))?'checked':'' ?> value="1"  class="message" type="checkbox" >
                                            <label for="subcheck8-3"><span></span>Edit  </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="custom-check">
                                            <input id="subcheck8-4" name="permission[messages][block]" <?php echo (!empty($messagePermission['blockp']))?'checked':'' ?> value="1" class="message"  type="checkbox" >
                                            <label for="subcheck8-4"><span></span>Block</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="custom-check">
                                            <input id="subcheck8-5" name="permission[messages][delete]" <?php echo (!empty($messagePermission['deletep']))?'checked':'' ?> value="1" class="message"  type="checkbox" >
                                            <label for="subcheck8-5"><span></span>Delete</label>
                                        </div>
                                    </li>
                                </ul>
                            </div>			
                            <div class="clear"></div>
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
                </div>
            </div>
        </div>
    </section>
</body>
<script>
    //function for give the permission to subadmin
    function permission(gettype, elementId) {
        var checked = $("#" + elementId).prop('checked');
        $("."+gettype).prop("checked", checked);       
    }
    
    //if inner checkboxes are checked then parent should be checked.
    $('document').ready(function(){
        $('#allcheckboxes .custom-check.main-check').each(function(){
            var parthis = $(this);
            $(this).find('ul.check-column li .custom-check input:checkbox').click(function(){
                var checkcnt = $(parthis).find('ul.check-column li .custom-check input:checkbox:checked').length;
                if(checkcnt == 0){
                    $(parthis).find('> input:checkbox').removeAttr('checked');
                }else if(checkcnt > 0){
                    $(parthis).find('> input:checkbox').prop('checked', true);
                }
                console.log(checkcnt+' == ');  
            });                     
        });
    });
    
</script>

<link href="public/cropper/cropper.min.css" rel="stylesheet">
<script>
    if (location.hostname == "localhost") {
        var domain = location.protocol + '//' + location.hostname + (location.port ? ':' + location.port : '') + '/smartguide/admin';
        var domain2 = location.protocol + '//' + location.hostname + (location.port ? ':' + location.port : '') + '/smartguide';
    }
    else {
        var domain = location.protocol + '//' + location.hostname + (location.port ? ':' + location.port : '') + '/admin';
        var domain2 = location.protocol + '//' + location.hostname + (location.port ? ':' + location.port : '');
    }

</script>
<script src="public/cropper/cropper.js"></script>
<script src="public/cropper/cropper.min.js"></script>
<script src="public/cropper/main.js"></script>
<script>
    function addCoverImage() {
        callme('coverPicInput','640','640','imagepicker2','addshopbtn','imageMe1','true','','circular');
    }
</script>