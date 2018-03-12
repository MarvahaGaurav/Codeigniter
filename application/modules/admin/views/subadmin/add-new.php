<link href="public/css/form-roles.css" rel='stylesheet'>
<body>

    <!-- Content -->
    <section class="inner-right-panel clearfix">

        <div class="breadcrumb-wrap">
            <ol class="breadcrumb">
                <li><a href="admin/subadmin">Sub Admins</a></li>
                <li class="active">Add Sub-admin</li>
            </ol>
        </div>	
        <div class="clear"></div>

        <div class="section">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-item-title clearfix">
                        <h3 class="title">Fill the below form</h3>
                    </div>
                    <!-- title and form upper action end-->
                    <?php echo form_open_multipart('',array('id'=>'subadmin_add'));?>
                    <br><br>
                    <div class="row">
                        <div class="col-lg-3 col-sm-4">
                            <label class="admin-label">Profile Picture<br></label>
                            <div class="image-view-wrapper img-view200p img-viewbdr-radius4p">
                                <div class="image-view img-view200">
                                    <div class="photo-upload-here">
                                        <img style="width: 100%;height: 100%;" class="profile-pic" id="profile_image" src="<?php echo DEFAULT_IMAGE ?>">
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
                    <div class="row">
                        <div class="user-detail-panel">
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="admin-label">Admin Name</label>
                                    <div class="input-holder">
                                        <input type="text" class="" maxlength="30" name="name" placeholder="* Sub-admin Name" value="<?php echo set_value('name'); ?>">
                                        <?php echo form_error('name', '<label class="alert-danger">', '</label>'); ?>   
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="admin-label">Email</label>
                                    <div class="input-holder">
                                        <input type="text" class="" maxlength="50" name="email" placeholder="* Sub-admin Email" value="<?php echo set_value('email'); ?>">
                                        <?php echo form_error('email', '<label class=" alert-danger">', '</label>'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="admin-label">Password</label>
                                    <div class="input-holder">
                                        <input type="password" class="" maxlength="16" name="password" placeholder="* Sub-admin Password" value="<?php echo set_value('password'); ?>">
                                        <?php echo form_error('password', '<label class=" alert-danger">', '</label>'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <label class="admin-label">Status</label>
                                <div class="commn-select-wrap">
                                    <select class="selectpicker" name="status">
                                        <option value="0">Inactive</option>
                                        <option value="1" selected>Active</option>
                                        <option value="2">Block</option>
                                    </select>
                                    <?php echo form_error('status', '<label class="alert-danger">', '</label>'); ?>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <div class="clearfix">
                                        <div class="row">
                                            <div class="col-lg-12"><h2 class="title-box m-t-n p-t-20">Sub-admin Roles :</h2></div>
                                            <div class="col-lg-12" id="allcheckboxes">
                                                <div class="custom-check main-check">
                                                    <input id="main-check1" name="user" onchange="permission('user', 'main-check1')"  value="1"   type="checkbox">
                                                    <label for="main-check1"><span></span>User Management</label>
                                                    <ul class="check-column">
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck1-1" name="permission[user][view]" value="1" class="user" type="checkbox">
                                                                <label for="subcheck1-1"><span></span>View </label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck1-2" name="permission[user][add]" value="1"  class="user" type="checkbox">
                                                                <label for="subcheck1-2"><span></span>Add</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck1-3" name="permission[user][edit]" value="1" class="user" type="checkbox">
                                                                <label for="subcheck1-3"><span></span>Edit</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck1-4" name="permission[user][block]" value="1" class="user" type="checkbox">
                                                                <label for="subcheck1-4"><span></span>Block</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck1-5" name="permission[user][delete]" value="1" class="user" type="checkbox">
                                                                <label for="subcheck1-5"><span></span>Delete</label>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>			
                                                <div class="clear"></div>
                                                <div class="custom-check main-check">
                                                    <input id="main-check2" name="merchant" onchange="permission('merchant', 'main-check2')"  value="2"   type="checkbox">
                                                    <label for="main-check2"><span></span>Merchant Management</label>
                                                    <ul class="check-column">
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck2-1" name="permission[merchant][view]" value="1" class="merchant" type="checkbox">
                                                                <label for="subcheck2-1"><span></span>View </label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck2-2" name="permission[merchant][add]" value="1"  class="merchant" type="checkbox">
                                                                <label for="subcheck2-2"><span></span>Add</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck2-3" name="permission[merchant][edit]" value="1" class="merchant" type="checkbox">
                                                                <label for="subcheck2-3"><span></span>Edit</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck2-4" name="permission[merchant][block]" value="1" class="merchant" type="checkbox">
                                                                <label for="subcheck2-4"><span></span>Block</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck2-5" name="permission[merchant][delete]" value="1" class="merchant" type="checkbox">
                                                                <label for="subcheck2-5"><span></span>Delete</label>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>			
                                                <div class="clear"></div>
                                                <div class="custom-check main-check">
                                                    <input id="main-check3" name="product" onchange="permission('product', 'main-check3')"  value="3"   type="checkbox">
                                                    <label for="main-check3"><span></span>Product Management</label>
                                                    <ul class="check-column">
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck3-1" name="permission[product][view]" value="1" class="product" type="checkbox">
                                                                <label for="subcheck3-1"><span></span>View </label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck3-2" name="permission[product][add]" value="1"  class="product" type="checkbox">
                                                                <label for="subcheck3-2"><span></span>Add</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck3-3" name="permission[product][edit]" value="1" class="product" type="checkbox">
                                                                <label for="subcheck3-3"><span></span>Edit</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck3-4" name="permission[product][block]" value="1" class="product" type="checkbox">
                                                                <label for="subcheck3-4"><span></span>Block</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck3-5" name="permission[product][delete]" value="1" class="product" type="checkbox">
                                                                <label for="subcheck3-5"><span></span>Delete</label>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>			
                                                <div class="clear"></div>
                                                <div class="custom-check main-check">
                                                    <input id="main-check4" name="template" onchange="permission('template', 'main-check4')"  value="4"   type="checkbox">
                                                    <label for="main-check4"><span></span>Template Management</label>
                                                    <ul class="check-column">
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck4-1" name="permission[template][view]" value="1" class="template" type="checkbox">
                                                                <label for="subcheck4-1"><span></span>View </label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck4-2" name="permission[template][add]" value="1"  class="template" type="checkbox">
                                                                <label for="subcheck4-2"><span></span>Add</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck4-3" name="permission[template][edit]" value="1" class="template" type="checkbox">
                                                                <label for="subcheck4-3"><span></span>Edit</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck4-4" name="permission[template][block]" value="1" class="template" type="checkbox">
                                                                <label for="subcheck4-4"><span></span>Block</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck4-5" name="permission[template][delete]" value="1" class="template" type="checkbox">
                                                                <label for="subcheck4-5"><span></span>Delete</label>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>			
                                                <div class="clear"></div>
                                                <div class="custom-check main-check">
                                                    <input id="main-check5" name="content" onchange="permission('content', 'main-check5')"  value="5"   type="checkbox">
                                                    <label for="main-check5"><span></span>Content Management</label>
                                                    <ul class="check-column">
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck5-1" name="permission[content][view]" value="1" class="content" type="checkbox">
                                                                <label for="subcheck5-1"><span></span>View </label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck5-2" name="permission[content][add]" value="1"  class="content" type="checkbox">
                                                                <label for="subcheck5-2"><span></span>Add</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck5-3" name="permission[content][edit]" value="1" class="content" type="checkbox">
                                                                <label for="subcheck5-3"><span></span>Edit</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck5-4" name="permission[content][block]" value="1" class="content" type="checkbox">
                                                                <label for="subcheck5-4"><span></span>Block</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck5-5" name="permission[content][delete]" value="1" class="content" type="checkbox">
                                                                <label for="subcheck5-5"><span></span>Delete</label>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>			
                                                <div class="clear"></div>
                                                <div class="custom-check main-check">
                                                    <input id="main-check6" name="option" onchange="permission('Version', 'main-check6')" value="6" type="checkbox">
                                                    <label for="main-check6"><span></span>Manage Version</label>
                                                    <ul class="check-column">
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck6-1" name="permission[version][view]" value="1"  class="Version" type="checkbox">
                                                                <label for="subcheck6-1"><span></span>View</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck6-4" name="permission[version][add]" value="1"  class="Version" type="checkbox">
                                                                <label for="subcheck6-4"><span></span>Add</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck6-2" name="permission[version][edit]" value="1" class="Version" type="checkbox">
                                                                <label for="subcheck6-2"><span></span>Edit </label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck6-5" name="permission[version][block]" value="1" class="Version" type="checkbox">
                                                                <label for="subcheck6-5"><span></span>Block </label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck6-3" name="permission[version][delete]" value="1" class="Version" type="checkbox">
                                                                <label for="subcheck6-3"><span></span>Delete </label>
                                                            </div>
                                                        </li>

                                                    </ul>
                                                </div>			
                                                <div class="clear"></div>
                                                <div class="custom-check main-check">
                                                    <input id="main-check7" name="portfolio" onchange="permission('Notification', 'main-check7')" value="7"  type="checkbox">
                                                    <label for="main-check7"><span></span>Manage Notifications </label>
                                                    <ul class="check-column">
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck7-1" name="permission[notification][view]" value="1"class="Notification"  type="checkbox">
                                                                <label for="subcheck7-1"><span></span>View </label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck7-2" name="permission[notification][add]" value="1"class="Notification"  type="checkbox">
                                                                <label for="subcheck7-2"><span></span>Add </label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck7-3" name="permission[notification][edit]" value="1" class="Notification"  type="checkbox">
                                                                <label for="subcheck7-3"><span></span>Edit or Resend</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck7-4" name="permission[notification][block]" value="1" class="Notification"  type="checkbox">
                                                                <label for="subcheck7-4"><span></span>Block</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck7-5" name="permission[notification][delete]" value="1" class="Notification"  type="checkbox">
                                                                <label for="subcheck7-5"><span></span>Delete</label>
                                                            </div>
                                                        </li>				
                                                    </ul>
                                                </div>
                                                <div class="clear"></div>
                                                <div class="custom-check main-check">
                                                    <input id="main-check8" name="option" onchange="permission('messages', 'main-check8')" value="8" type="checkbox">
                                                    <label for="main-check8"><span></span>Manage Messages</label>
                                                    <ul class="check-column">
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck8-1" name="permission[messages][view]" value="1"  class="messages" type="checkbox">
                                                                <label for="subcheck8-1"><span></span>View</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck8-4" name="permission[messages][add]" value="1"  class="messages" type="checkbox">
                                                                <label for="subcheck8-4"><span></span>Add</label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck8-2" name="permission[messages][edit]" value="1" class="messages" type="checkbox">
                                                                <label for="subcheck8-2"><span></span>Edit </label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck8-5" name="permission[messages][block]" value="1" class="messages" type="checkbox">
                                                                <label for="subcheck8-5"><span></span>Block </label>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="custom-check">
                                                                <input id="subcheck8-3" name="permission[messages][delete]" value="1" class="messages" type="checkbox">
                                                                <label for="subcheck8-3"><span></span>Delete </label>
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
                </div>
            </div>
            <!--close form view   -->
            <?php echo form_close(); ?>
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
<!--cropper libraries-->
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
