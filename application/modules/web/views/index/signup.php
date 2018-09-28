<body class="form-backgournd">

    <div class="form-bg"></div>
    <!-- ============== Signup Section ============== -->
    <div class="fm-table-section">

        <!-- ============== Right Section ============== -->
        <div class="fm-table-cell tbl-cell-2 skewpart">
            <div class="form-right-cell">
                <div class="skewoverlay"></div>
                <div class="form-logo"><img src="public/images/logo.png" alt="logo"></div>
                <h2>Welcome</h2>
                <p>SG Lighting has vast experience of and expertise in a wide range of different types of projects such as indoor & outdoor lighting.</p>
            </div>
        </div>
        <!-- //Right Cell -->

        <!-- ============== Left Section ============== -->
        <div class="fm-table-cell fm-cell tbl-cell-1">
            <div class="form-wrapper">
                <?php echo form_open(current_url(), ['id' => 'signup-form']) ?>
                    <h1 class="form-caption">Signup</h1>
                    <p class="form-desciption">Have an account? <a href="login.html" class="create-account">Login Now</a></p>

                    <!-- thumb upload -->
                    <div class="image-wrapper">
                        <div class="image-view-box img-view130p img-viewbdr-radius">
                            <div id="image-view" class="image-view img-view130" style="background-image:url(../images/user.png);"></div>                                    
                        </div>
                        <div class="upload-btn">
                            <input type="file" id="upload">
                            <img src="public/images/camera.svg" />
                        </div>
                    </div>
                    <!-- thumb upload -->

                    <!-- Business User -->
                    <div class="business">
                        <div class="form-group-inline clearfix">
                            <div class="form-group">
                                <select id="select-user-types" name="user_type">
                                    <option value="<?php echo PRIVATE_USER ?>"><?php echo $this->lang->line('private_user') ?></option>
                                    <option value="<?php echo BUSINESS_USER ?>"><?php echo $this->lang->line('business_user') ?></option>
                                    <option value="<?php echo INSTALLER ?>"><?php echo $this->lang->line('installer') ?></option>
                                    <option value="<?php echo ARCHITECT ?>"><?php echo $this->lang->line('architect') ?></option>
                                    <option value="<?php echo ELECTRICAL_PLANNER ?>"><?php echo $this->lang->line('electrical_planner') ?></option>
                                    <option value="<?php echo WHOLESALER ?>"><?php echo $this->lang->line('wholesaler') ?></option>
                                </select>
                                <span class="fs-caret"></span>
                            </div>
                        </div>

                        <div class="form-group-inline clearfix">
                            <div class="form-group">
                                <input type="text" class="form-control" name="fullname" placeholder="Full Name" required="" autofocus="" />
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control" name="email" placeholder="Email Address" required="" autofocus="" />
                            </div>
                        </div>

                        <div class="form-group-inline clearfix">
                            <div class="form-group">
                                <input type="password" class="form-control" name="password" id="password" placeholder="Password" required=""/>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password" required=""/>
                            </div>
                        </div>

                        <div class="form-group-inline clearfix">
                            <div class="form-group">
                                <select name="contact_number_code" class="contact-number">
                                <?php foreach ($countries as $country) {  ?>
                                    <option value="<?php echo $country['calling_code'] ?>"><?php echo "+{$country['calling_code']}" ?></option>
                                <?php  } ?>
                                </select>
                                <input type="number" class="form-control contact-number" name="contact_number" placeholder="Contact Number" required="" autofocus="" />
                            </div>
                            <div class="form-group">
                                <select name="alternate_contact_number_code" class="contact-number">
                                <?php foreach ($countries as $country) {  ?>
                                    <option value="<?php echo $country['calling_code'] ?>"><?php echo "+{$country['calling_code']}" ?></option>
                                <?php  } ?>
                                </select>
                                <input type="number" class="form-control contact-number" name="alternate_contact_number" placeholder="Alternate Number" required=""/>
                            </div>
                        </div>

                        <!-- wholesaler -->
                        <div class="wholesaler-field" id="technician-div">
                            <div class="clearfix">
                                <label>Are you the owner of this company?</label>
                                <div class="clearfix"></div>
                                <div class="custom-radio-wrapper">
                                    <label class="custom-radio">Yes
                                        <input type="radio" value="1" class="technician-fields" checked="checked" name="is_company_owner" disabled="disabled">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="custom-radio">No
                                        <input type="radio" value="0" class="technician-fields" name="is_company_owner" disabled="disabled">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>  
                            </div>
                            <div class="form-group-inline clearfix">
                                <div class="form-group company-owner-fields">
                                    <input type="number" id="company-registration-number" name="company_registration_number" class="form-control technician-fields" placeholder="Company Registration Number"  disabled="disabled"/>
                                </div>
                                <div class="form-group">
                                    <input type="text" id="company-name" name="company_name" class="form-control technician-fields" placeholder="Company Name" disabled="disabled"/>
                                </div>
                            </div>
                            <div class="form-group clearfix company-owner-fields">
                                <div class="chooseFile">
                                    <input id="uploadfile" id="company-logo" name="company_logo" class="form-control technician-fields" placeholder="Choose File" disabled="display" disabled="disabled">
                                    <div class="uploadfile-wrap">
                                        <input type="file" id="uploadbtn">
                                        <span>Browse</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- wholesaler end -->

                        <!-- installer -->
                        <div class="installer-field">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Company Name" />
                            </div>
                        </div>
                        <!-- installer end -->

                        <div class="form-group-inline clearfix">
                            <div class="form-group">
                                <select class="country" name="country">
                                    <option value=""><?php echo $this->lang->line('select_a_country') ?></option>
                                <?php foreach ($countries as $country) {  ?>
                                    <option value="<?php echo $country['country_code1'] ?>"><?php echo "{$country['name']}" ?></option>
                                <?php  } ?>
                                </select>
                                <span class="fs-caret"></span>
                            </div>
                            <div class="form-group">
                                <div class="input-wrapper city-wrapper">
                                    <span class="fa fa-spin fa-circle-o-notch city-loader concealable"></span>
                                    <input type="text" placeholder="Please start typing your city..." id="select-city" name="city_name" data-country="" value="">
                                </div>
                                <input type="hidden" name="city" id="city-id" value="">
                            </div>
                        </div>

                        <div class="form-group-inline clearfix">
                            <div class="form-group no-margin">
                                <input type="number" class="form-control" name="zipcode" placeholder="Zipcode" required="" autofocus="" />
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-btn-wrap">
                        <button class="form-btn save" type="submit">Signup</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- //Left Cell -->

    </div>
    <!-- ============== Login Section End ============== -->

</body>