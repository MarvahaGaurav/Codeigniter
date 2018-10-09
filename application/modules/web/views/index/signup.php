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
                    <p class="form-desciption">Have an account? <a href="<?php echo base_url('/login') ?>" class="create-account">Login Now</a></p>

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
                                <select id="select-user-types" name="user_type" class="select-user-types" data-style="btn-default custom-select-style">
                                    <option value="<?php echo PRIVATE_USER ?>" <?php echo set_select('user_type', PRIVATE_USER, true) ?>><?php echo $this->lang->line('private_user') ?></option>
                                    <option value="<?php echo BUSINESS_USER ?>" <?php echo set_select('user_type', BUSINESS_USER) ?>><?php echo $this->lang->line('business_user') ?></option>
                                    <option value="<?php echo INSTALLER ?>" <?php echo set_select('user_type', INSTALLER) ?>><?php echo $this->lang->line('installer') ?></option>
                                    <option value="<?php echo ARCHITECT ?>" <?php echo set_select('user_type', ARCHITECT) ?>><?php echo $this->lang->line('architect') ?></option>
                                    <option value="<?php echo ELECTRICAL_PLANNER ?>" <?php echo set_select('user_type', ELECTRICAL_PLANNER) ?>><?php echo $this->lang->line('electrical_planner') ?></option>
                                    <option value="<?php echo WHOLESALER ?>" <?php echo set_select('user_type', WHOLESALER) ?>><?php echo $this->lang->line('wholesaler') ?></option>
                                </select>
                                <?php echo form_error('user_type', '<label for="user_type" class="error">', "</label>"); ?>
                            </div>
                        </div>

                        <div class="form-group-inline clearfix">
                            <div class="form-group">
                                <input type="text" class="form-control" name="fullname" placeholder="Full Name" value="<?php echo set_value('fullname') ?>" autofocus="" />
                                <?php echo form_error('fullname', '<label for="fullname" class="error">', "</label>"); ?>
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control" name="email" placeholder="Email Address" autofocus="" value="<?php echo set_value("email") ?>"/>
                                <?php echo form_error('email', '<label for="email" class="error">', "</label>"); ?>
                            </div>
                        </div>

                        <div class="form-group-inline clearfix">
                            <div class="form-group">
                                <input type="password" class="form-control" name="password" id="password" placeholder="Password"/>
                                <?php echo form_error('password', '<label for="password" class="error">', "</label>"); ?>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password"/>
                                <?php echo form_error('confirm_password', '<label for="confirm_password" class="error">', "</label>"); ?>
                            </div>
                        </div>

                        <div class="form-group-inline clearfix">
                            <div class="form-group">
                                <select name="contact_number_code" class="contact-number" data-style="btn-default custom-select-style">
                                <?php foreach ($countries as $country) {  ?>
                                    <option value="<?php echo $country['calling_code'] ?>" <?php echo set_select('contact_number_code', $country['calling_code']) ?>><?php echo "+{$country['calling_code']}" ?></option>
                                <?php  } ?>
                                </select>
                                <input type="number" class="form-control contact-number-input" name="contact_number" value="<?php echo set_value('contact_number') ?>" placeholder="Contact Number" autofocus="" />
                                <?php echo form_error('contact_number', '<label for="contact_number" class="error">', "</label>"); ?>
                            </div>
                            <div class="form-group">
                                <select name="alternate_contact_number_code" class="contact-number" data-style="btn-default custom-select-style">
                                <?php foreach ($countries as $country) {  ?>
                                    <option value="<?php echo $country['calling_code'] ?>" <?php echo set_select('alternate_contact_number_code', $country['calling_code']) ?>><?php echo "+{$country['calling_code']}" ?></option>
                                <?php  } ?>
                                </select>
                                <input type="number" class="form-control contact-number-input" name="alternate_contact_number" placeholder="Alternate Number" value="<?php echo set_value('alternate_contact_number') ?>"/>
                                <?php echo form_error('alternate_contact_number', '<label for="alternate_contact_number" class="error">', "</label>"); ?>
                            </div>
                        </div>
                        <?php $isCustomerUser = in_array(set_value('user_type'), [PRIVATE_USER, BUSINESS_USER])||empty(set_value('user_type')) ?>
                        <?php $isCompanyOwner = (int)set_value('is_company_owner')===1||empty(set_value('user_type')) ?>
                        <?php  ?>
                        <!-- wholesaler -->
                        <div class="<?php echo $isCustomerUser?"wholesaler-field":"" ?>" id="technician-div">
                            <div class="clearfix">
                                <label>Are you the owner of this company?</label>
                                <div class="clearfix"></div>
                                <div class="custom-radio-wrapper">
                                    <label class="custom-radio">Yes
                                        <input type="radio" value="1" class="technician-fields" name="is_company_owner" <?php echo set_radio('is_company_owner', '1', true) ?> <?php echo $isCustomerUser?"disabled":'' ?>>
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="custom-radio">No
                                        <input type="radio" value="0" class="technician-fields" name="is_company_owner" <?php echo set_radio('is_company_owner', '0') ?> <?php echo $isCustomerUser?"disabled":'' ?>>
                                        <span class="checkmark"></span>
                                    </label>
                                </div>  
                            </div>
                            <div class="form-group-inline clearfix">
                                <div class="form-group company-owner-wrapper <?php echo $isCompanyOwner ?"":"concealable" ?>">
                                    <input type="number" value="<?php echo set_value('company_registration_number') ?>" id="company-registration-number" name="company_registration_number" class="form-control company-owner-field technician-fields" placeholder="Company Registration Number" <?php echo $isCustomerUser||!$isCompanyOwner?"disabled":'' ?>/>
                                    <?php echo form_error('company_registration_number', '<label for="company_registration_number" class="error">', "</label>"); ?>
                                </div>
                                <div class="form-group" id="company-name-wrapper">
                                    <?php if ($isCompanyOwner) { ?>
                                    <input type="text" value="<?php echo set_value('company_name') ?>" id="company-name" name="company_name" class="form-control technician-fields" placeholder="Company Name" <?php echo $isCustomerUser?"disabled":'' ?>/>
                                    <?php echo form_error('company_name', '<label for="company_name" class="error">', "</label>"); ?>
                                    <?php } else if (is_numeric(set_value('is_company_owner')) && (int)set_value('is_company_owner') === 0) { ?>
                                        <?php $companyData = fetch_company_data(); ?>
                                        <select name="company_name" class="company-name-select" data-style="btn-default custom-select-style">
                                            <option value="">Select a country</option>
                                            <?php foreach ($companyData as $company) { ?>
                                                <option data-thumbnail="<?php echo $company['company_image'] ?>" value="<?php echo $company['company_id'] ?>" <?php echo set_select('company_name', $company['company_id']) ?>><?php echo $company['company_name'] ?></option>
                                            <?php }?>
                                        </select>
                                        <?php echo form_error('company_name', '<label for="company_name" class="error">', "</label>"); ?>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="form-group clearfix company-owner-wrapper <?php echo $isCompanyOwner ?"":"concealable" ?>">
                                <div class="chooseFile">
                                    <input id="uploadfile" id="company-logo" name="company_logo" class="form-control company-owner-field technician-fields" placeholder="Choose File" <?php echo $isCustomerUser||!$isCompanyOwner?"disabled":'' ?>/>
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
                                <select class="country" name="country" data-style="btn-default custom-select-style">
                                    <option value=""><?php echo $this->lang->line('select_a_country') ?></option>
                                <?php foreach ($countries as $country) {  ?>
                                    <option value="<?php echo $country['country_code1'] ?>" <?php echo set_select('country', $country['country_code1']) ?>><?php echo "{$country['name']}" ?></option>
                                <?php  } ?>
                                </select>
                                <?php echo form_error('country', '<label for="country" class="error">', "</label>"); ?>
                            </div>
                            <div class="form-group">
                                <div class="input-wrapper city-wrapper">
                                    <span class="fa fa-spin fa-circle-o-notch city-loader concealable"></span>
                                    <input type="text" placeholder="Please start typing your city..." id="select-city" name="city_name" data-country="<?php echo set_value('country') ?>" value="<?php echo set_value('city_name') ?>">
                                    <?php echo form_error('city', '<label for="city" class="error">', "</label>"); ?>
                                </div>
                                <input type="hidden" name="city" id="city-id" value="<?php echo set_value('city') ?>">
                            </div>
                        </div>

                        <div class="form-group-inline clearfix">
                            <div class="form-group no-margin">
                                <input type="number" class="form-control" name="zipcode" placeholder="Zipcode" autofocus="" value="<?php echo set_value('zipcode') ?>"/>
                                <?php echo form_error('zipcode', '<label for="zipcode" class="error">', "</label>"); ?>
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