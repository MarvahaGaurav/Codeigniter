<div class="inner-container">
    <div class="container">

        <!-- breadcrumb -->
        <ul class="breadcrumb">
            <li>
                <a href="<?php echo base_url() ?>">Home</a>
            </li>
            <li>
                <a href="<?php echo base_url("/home/profile/" . encryptDecrypt($user['user_id'])) ?>">My Profile</a>
            </li>
            <li class="active">Edit Profile</li>
        </ul>
        <!-- //breadcrumb -->

        <div class="page-heading">
            <h1 class="page-title">Edit Profile</h1>
        </div>

        <!-- Technician Profile Detail -->
        <div class="technician-profile-wrapper">
            <div class="row">
                <div class="col-sm-3 col-xs-12">
                    <div class="profile-thumb">
                        <!-- Thumb Wrapper -->
                        <div class="thumb-view-wrapper thumb-view-p5 img-viewbdr-radius">
                            <div class="thumb-view thumb-viewfullheight-5" style="background:url('<?php echo isset($user['image'])&&!empty($user['image'])?$user['image']:base_url("public/images/missing_avatar.svg") ?>')"></div>
                        </div>
                        <!-- //Thumb Wrapper -->
                        <label class="browse-btn">
                            <input type="file" id="uploadbtn">
                            <span class="custom-btn btn-margin btn-width save">Browse Image</span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-9 col-xs-12">

                    <!-- User detail Block wise -->
                    <div class="user-detail-block2">
                        <div class="row">
                            <div class="col-xs-12">
                                <h3 class="userblock-caption">Basic Details</h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="p-label">Name</label>
                                    <div class="form-group-field">
                                        <input type="text" value="<?php echo $user['first_name'] ?>" name="name" placeholder="Company Name">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="p-label">Email ID</label>
                                    <div class="form-group-field">
                                        <input type="text" value="<?php echo $user['email'] ?>" name="email"  placeholder="Email ID">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- User detail Block wise -->
                    <div class="user-detail-block2">
                        <div class="row">
                            <div class="col-lg-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="p-label">Contact Number</label>
                                    <div class="form-group-field">
                                        <input type="text" value="<?php echo "+ {$user['prm_user_countrycode']} {$user['phone']}" ?>" name="phone" placeholder="Contact Number">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="p-label">Alternate Number</label>
                                    <div class="form-group-field">
                                        <input type="text" value="<?php echo "+ {$user['alt_user_countrycode']} {$user['alt_userphone']}" ?>" name="alt_phone" placeholder="Alternate Number">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- User detail Block wise end -->

                    <!-- User detail Block wise -->
                    <div class="user-detail-block2">
                        <div class="row">
                            <div class="col-xs-12">
                                <h3 class="userblock-caption">Address Details</h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="p-label">Country</label>
                                    <div class="form-group-field">
                                        <select class="select-filed-name2 country" data-live-search="true">
                                        <?php foreach ( $countries as $country ) :?>
                                            <option value="<?php echo $country['country_code1'] ?>" <?php echo $country['country_code1']==$user['country_id']?"selected":"" ?> ><?php echo $country['name'] ?></option>
                                        <?php endforeach?>
                                        </select>
                                        <span class="customArrow"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="p-label">City</label>
                                    <div class="form-group-field">
                                        <select class="select-filed-name2 cities" data-live-search="true">
                                        <?php foreach ( $cities as $city ) :?>
                                            <option value="<?php echo $city['id'] ?>" <?php echo $city['id']==$user['city_id']?"selected":"" ?>><?php echo $city['name'] ?></option>
                                        <?php endforeach?>
                                        </select>
                                        <span class="customArrow"></span>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <!-- User detail Block wise end -->

                    <!-- User detail Block wise -->
                    <div class="user-detail-block2">
                        <div class="row">
                            <div class="col-lg-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="p-label">Zip Code</label>
                                    <div class="form-group-field">
                                        <input type="text" name="zip_code" value="<?php echo $user['zipcode'] ?>" placeholder="12131321321">
                                    </div>
                                </div>
                            </div>

                            <!-- button wrapper -->
                            <div class="col-xs-12">
                                <div class="button-wrapper">
                                    <input type="submit" value="Save" class="custom-btn btn-margin btn-width save">
                                    <button type="submit" class="custom-btn btn-margin btn-width cancel">Cancel</button>
                                </div>
                            </div>
                            <!-- button wrapper end -->
                        </div>
                    </div>
                    <!-- User detail Block wise -->

                </div>

            </div>
        </div>
        <!-- Technician Profile Detail End -->

    </div>
</div>