<div class="inner-container">
    <div class="container">

        <!-- breadcrumb -->
        <ul class="breadcrumb">
            <li>
                <a href="javascript:void(0)">Home</a>
            </li>
            <li>
                <a href="<?php echo base_url("home/technicians") ?>">Manage Technician</a>
            </li>
            <li class="active">Technician Profile</li>
        </ul>
        <!-- //breadcrumb -->

        <div class="page-heading">
            <h1 class="page-title">Technician Profile</h1>
        </div>

        <!-- Technician Profile Detail -->
        <div class="technician-profile-wrapper">
            <div class="row">
                <div class="col-sm-3 col-xs-12">
                    <div class="profile-thumb">
                        <!-- Thumb Wrapper -->
                        <div class="thumb-view-wrapper thumb-view-p5 img-viewbdr-radius3px">
                            <div class="thumb-view thumb-viewfullheight-5" style="background:url('<?php echo $technician['image'] ?>')"></div>
                        </div>
                        <!-- //Thumb Wrapper -->
                        <h3 class="profile-username"><?php echo $technician['first_name'] ?></h3>
                        <p>Technician</p>
                    </div>
                </div>
                <div class="col-sm-9 col-xs-12">
                    <!-- User detail Block wise -->
                    <div class="user-detail-block">
                        <div class="row">
                            <div class="col-xs-12">
                                <h3 class="userblock-caption">Basic Details</h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label class="p-label">Email ID</label>
                                    <div class="input-holder">
                                        <span class="p-label-value"><?php echo $technician['email'] ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label class="p-label">Mobile Number</label>
                                    <div class="input-holder">
                                        <span class="p-label-value"><?php echo "+ {$technician['prm_user_countrycode']} {$technician['phone']}" ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label class="p-label">Alternate Number</label>
                                    <div class="input-holder">
                                        <span class="p-label-value"><?php echo "+ {$technician['alt_user_countrycode']} {$technician['alt_userphone']}" ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- User detail Block wise end -->

                    <!-- User detail Block wise -->
                    <div class="user-detail-block">
                        <div class="row">
                            <div class="col-xs-12">
                                <h3 class="userblock-caption">Address Details</h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label class="p-label">City</label>
                                    <div class="input-holder">
                                        <span class="p-label-value"><?php echo $technician['city'] ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label class="p-label">Country</label>
                                    <div class="input-holder">
                                        <span class="p-label-value"><?php echo $technician['country'] ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label class="p-label">Zipcode</label>
                                    <div class="input-holder">
                                        <span class="p-label-value"><?php echo $technician['zipcode'] ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- User detail Block wise -->

                    <!-- User detail Block wise -->
                    <?php if (EMPLOYEE_REQUEST_ACCEPTED === (int)$technician['request_status']) {?>
                    <?php echo form_open("home/technicians/" . encryptDecrypt($technician['id'])) ?>
                        <div class="user-detail-block">
                            <div class="row">
                                <div class="col-xs-12">
                                    <h3 class="userblock-caption">Grant Permission</h3>
                                </div>
                            </div>

                            <!-- user-permission-type-block -->
                            <div class="row user-permission-type-block">
                                <div class="col-xs-12 user-permission-type">
                                    <h4>Quote</h4>
                                </div>
                                <div class="col-lg-3 col-sm-3 col-xs-12">
                                    <div class="form-group clearfix">
                                        <label class="custom-control custom-checkbox pull-left">
                                            <input id="quote-view" type="checkbox" value="1" name="quote_view" <?php echo $technician['quote_view'] == 1?"checked":"" ?> class="custom-control-input">
                                            <span class="custom-control-indicator"></span>
                                        </label>
                                        <label for="quote-view" class="clickable remember">View</label>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-3 col-xs-12">
                                    <div class="form-group clearfix">
                                        <label class="custom-control custom-checkbox pull-left">
                                            <input id="quote-add" type="checkbox" value="1" name="quote_add" <?php echo $technician['quote_add'] == 1?"checked":"" ?> class="custom-control-input">
                                            <span class="custom-control-indicator"></span>
                                        </label>
                                        <label for="quote-add" class="clickable remember">Add</label>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-3 col-xs-12">
                                    <div class="form-group clearfix">
                                        <label class="custom-control custom-checkbox pull-left">
                                            <input id="quote-edit" type="checkbox" value="1" name="quote_edit" <?php echo $technician['quote_edit'] == 1?"checked":"" ?> class="custom-control-input">
                                            <span class="custom-control-indicator"></span>
                                        </label>
                                        <label for="quote-edit" class="clickable remember">Edit</label>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-3 col-xs-12">
                                    <div class="form-group clearfix">
                                        <label class="custom-control custom-checkbox pull-left">
                                            <input id="quote-delete" type="checkbox" value="1" name="quote_delete" <?php echo $technician['quote_delete'] == 1?"checked":"" ?> class="custom-control-input">
                                            <span class="custom-control-indicator"></span>
                                        </label>
                                        <label for="quote-delete" class="clickable remember">Delete</label>
                                    </div>
                                </div>
                            </div>
                            <!-- user-permission-type-block end -->

                            <!-- user-permission-type-block -->
                            <div class="row user-permission-type-block">
                                <div class="col-xs-12 user-permission-type">
                                    <h4>Inspiration</h4>
                                </div>
                                <div class="col-lg-3 col-sm-3 col-xs-12">
                                    <div class="form-group clearfix">
                                        <label class="custom-control custom-checkbox pull-left">
                                            <input id="inspiration-view" type="checkbox" value="1" name="inspiration_view" <?php echo $technician['insp_view'] == 1?"checked":"" ?> class="custom-control-input">
                                            <span class="custom-control-indicator"></span>
                                        </label>
                                        <label for="inspiration-view" class="clickable remember">View</label>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-3 col-xs-12">
                                    <div class="form-group clearfix">
                                        <label class="custom-control custom-checkbox pull-left">
                                            <input id="inspiration-add" type="checkbox" value="1" name="inspiration_add" <?php echo $technician['insp_add'] == 1?"checked":"" ?> class="custom-control-input">
                                            <span class="custom-control-indicator"></span>
                                        </label>
                                        <label for="inspiration-add" class="clickable remember">Add</label>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-3 col-xs-12">
                                    <div class="form-group clearfix">
                                        <label class="custom-control custom-checkbox pull-left">
                                            <input id="inspiration-edit" type="checkbox" value="1" name="inspiration_edit" <?php echo $technician['insp_edit'] == 1?"checked":"" ?> class="custom-control-input">
                                            <span class="custom-control-indicator"></span>
                                        </label>
                                        <label for="inspiration-edit" class="clickable remember">Edit</label>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-3 col-xs-12">
                                    <div class="form-group clearfix">
                                        <label class="custom-control custom-checkbox pull-left">
                                            <input id="inspiration-delete" type="checkbox" value="1" name="inspiration_delete" <?php echo $technician['insp_delete'] == 1?"checked":"" ?> class="custom-control-input">
                                            <span class="custom-control-indicator"></span>
                                        </label>
                                        <label for="inspiration-delete" class="clickable remember">Delete</label>
                                    </div>
                                </div>
                            </div>
                            <!-- user-permission-type-block end -->

                            <!-- user-permission-type-block -->
                            <div class="row user-permission-type-block">
                                <div class="col-xs-12 user-permission-type">
                                    <h4>Project</h4>
                                </div>
                                <div class="col-lg-3 col-sm-3 col-xs-12">
                                    <div class="form-group clearfix">
                                        <label class="custom-control custom-checkbox pull-left">
                                            <input id="project-view" type="checkbox" value="1" name="project_view" <?php echo $technician['project_view'] == 1?"checked":"" ?> class="custom-control-input">
                                            <span class="custom-control-indicator"></span>
                                        </label>
                                        <label for="project-view" class="clickable remember">View</label>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-3 col-xs-12">
                                    <div class="form-group clearfix">
                                        <label class="custom-control custom-checkbox pull-left">
                                            <input id="project-add" type="checkbox" value="1" name="project_add" <?php echo $technician['project_add'] == 1?"checked":"" ?> class="custom-control-input">
                                            <span class="custom-control-indicator"></span>
                                        </label>
                                        <label for="project-add" class="clickable remember">Add</label>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-3 col-xs-12">
                                    <div class="form-group clearfix">
                                        <label class="custom-control custom-checkbox pull-left">
                                            <input id="project-edit" type="checkbox" value="1" name="project_edit" <?php echo $technician['project_edit'] == 1?"checked":"" ?> class="custom-control-input">
                                            <span class="custom-control-indicator"></span>
                                        </label>
                                        <label for="project-edit" class="clickable remember">Edit</label>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-3 col-xs-12">
                                    <div class="form-group clearfix">
                                        <label class="custom-control custom-checkbox pull-left">
                                            <input id="project-delete" type="checkbox" value="1" name="project_delete" <?php echo $technician['project_delete'] == 1?"checked":"" ?> class="custom-control-input">
                                            <span class="custom-control-indicator"></span>
                                        </label>
                                        <label for="project-delete" class="clickable remember">Delete</label>
                                    </div>
                                </div>
                            </div>
                            <!-- user-permission-type-block end -->

                            <!-- user-permission-type-block -->
                            <div class="row user-permission-type-block">
                                <div class="col-xs-12">
                                    <div class="button-wrapper">
                                        <input type="submit" name="permissions_submit" value="Save" class="custom-btn btn-margin btn-width save">
                                        <button type="button" class="custom-btn btn-margin btn-width cancel back-button" data-redirect="<?php echo base_url("home/technicians") ?>">Cancel</button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>
                    <?php }?>
                    <!-- User detail Block wise -->

                </div>
            </div>
            <!-- Technician Profile Detail End -->

        </div>
    </div>