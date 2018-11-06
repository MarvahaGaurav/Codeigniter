<div class="inner-container">
    <div class="container">

        <!-- breadcrumb -->
        <ul class="breadcrumb">
            <li><a href="javascript:void(0)">Home</a></li>
            <li class="active">Settings</li>
        </ul>
        <!-- //breadcrumb -->
        <div id="password-error" data-message="<?php echo form_error("old_password") . form_error("new_password") . form_error("confirm_password") ?>"></div>
        <div class="page-heading">
            <h1 class="page-title">Settings</h1>
            <p class="prj-description">
                We are continously designing, prototyping and testing new products to enable us to deliver products that are energy efficient and environmental friendly, in combination
                with a creation of the ambiance that you need, always keeping in mind that luminaires have a great impact on the environment, appearance and impression of the overall
                surroundings.
            </p>
        </div>

        <!-- Caption before section -->
        <!--<div class="section-title section-border">
            <h3>Invite People</h3>
        </div>
        

        <div class="invite-people setting-section">
            <p>Click on Invite button to invite your friends to get connect with SG Lightings.</p>
            <div class="button-wrapper">
                <button type="button" class="custom-btn btn-margin btn-width save" data-toggle="modal" data-target="#invite-people">Invite</button>
            </div>
        </div>-->
        <!-- //Caption before section end -->

        
        <?php echo form_open('home/settings/' . encryptDecrypt($user['user_id']), ['id' => 'settings-form'])  ?>
            <?php if (INSTALLER === (int)$user['user_type'] && ROLE_OWNER === (int)$user['is_owner'] ) : ?>
            <!-- Caption before section -->
            <div class="section-title section-border">
                <h3>Pricing</h3>
            </div>
            <!-- //Caption before section end -->

            <!-- select pricing wrapper -->
            <div class="select-pricing-wrapper setting-section">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label class="labelTxt">Set Pricing</label>
                            <div class="form-group-field">
                                <select>
                                    <option>20%</option>
                                    <option>30%</option>
                                    <option>40%</option>
                                    <option>50%</option>
                                </select>
                                <span class="customArrow"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- select pricing wrapper end -->
            <?php endif ?>
            <!-- Caption before section -->
            <!-- <div class="section-title section-border">
                <h3>Change Language</h3>
            </div> -->
            <!-- //Caption before section end -->

            <!-- Language Wrapper -->
            <!-- <div class="language-wrapper">
                <div class="language-divide setting-section">
                    <ul>
                        <li>
                            <div class="language-list">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="main-check custom-control custom-radio">
                                            <input id="main-check1" name="language"  value="da" <?php echo $user['language'] == "da" ? "checked": "" ?> class="custom-control-input"  type="radio">
                                            <label for="main-check1">
                                                <span class="custom-control-indicator"></span>
                                            </label>
                                            <div class="custom-control custom-radio">
                                                <label><strong class="op-semibold">Danish</strong></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="language-list">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="main-check custom-control custom-radio">
                                            <input id="main-check2" name="language"  value="nl" <?php echo $user['language'] == "nl" ? "checked": "" ?> class="custom-control-input"  type="radio">
                                            <label for="main-check2">
                                                <span class="custom-control-indicator"></span>
                                            </label>
                                            <div class="custom-control custom-radio">
                                                <label><strong class="op-semibold">Dutch</strong></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="language-list">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="main-check custom-control custom-radio">
                                            <input id="main-check3" name="language"  value="en" <?php echo $user['language'] == "en" ? "checked": "" ?> class="custom-control-input"  type="radio">
                                            <label for="main-check3">
                                                <span class="custom-control-indicator"></span>
                                            </label>
                                            <div class="custom-control custom-radio">
                                                <label><strong class="op-semibold">English</strong></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="language-list">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="main-check custom-control custom-radio">
                                            <input id="main-check4" name="language"  value="fi" <?php echo $user['language'] == "fi" ? "checked": "" ?> class="custom-control-input"  type="radio">
                                            <label for="main-check4">
                                                <span class="custom-control-indicator"></span>
                                            </label>
                                            <div class="custom-control custom-radio">
                                                <label><strong class="op-semibold">Finnish</strong></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="language-list">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="main-check custom-control custom-radio">
                                            <input id="main-check5" name="language"  value="fr" <?php echo $user['language'] == "fr" ? "checked": "" ?> class="custom-control-input"  type="radio">
                                            <label for="main-check5">
                                                <span class="custom-control-indicator"></span>
                                            </label>
                                            <div class="custom-control custom-radio">
                                                <label><strong class="op-semibold">French</strong></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="language-list">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="main-check custom-control custom-radio">
                                            <input id="main-check6" name="language"  value="de" <?php echo $user['language'] == "de" ? "checked": "" ?> class="custom-control-input"  type="radio">
                                            <label for="main-check6">
                                                <span class="custom-control-indicator"></span>
                                                <strong class="op-semibold">Finland</strong>
                                            </label>
                                            <div class="custom-control custom-radio">
                                                <label><strong class="op-semibold">German</strong></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="language-list">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="main-check custom-control custom-radio">
                                            <input id="main-check7" name="language"  value="nb" <?php echo $user['language'] == "nb" ? "checked": "" ?> class="custom-control-input"  type="radio">
                                            <label for="main-check7">
                                                <span class="custom-control-indicator"></span>
                                                <strong class="op-semibold">Finland</strong>
                                            </label>
                                            <div class="custom-control custom-radio">
                                                <label><strong class="op-semibold">Norwegian</strong></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="language-list">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="main-check custom-control custom-radio">
                                            <input id="main-check8" name="language"  value="sv" <?php echo $user['language'] == "sv" ? "checked": "" ?> class="custom-control-input"  type="radio">
                                            <label for="main-check8">
                                                <span class="custom-control-indicator"></span>
                                                <strong class="op-semibold">Finland</strong>
                                            </label>
                                            <div class="custom-control custom-radio">
                                                <label><strong class="op-semibold">Swedish</strong></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        
                        
                    </ul>
                    <div class="clearfix"></div>
                </div>
            </div> -->

            <!-- Caption before section -->
            <!-- <div class="section-title section-border">
                <h3>Change Currency</h3>
            </div> -->
            <!-- //Caption before section end -->

            <!-- currency wrapper -->
            <!-- <div class="currency-wrapper setting-section2">
                <div class="change-currency">
                    <ul>
                        <li>
                            <div class="custom-control custom-radio">
                                <input id="currency1" name="currency"  value="DKK" <?php echo $user['currency'] == "DKK" ? "checked": "" ?> class="custom-control-input"  type="radio">
                                <label for="currency1">
                                    <span class="custom-control-indicator"></span>
                                    <img src="<?php echo base_url('/public/images/currency1.png') ?>" alt="Country Logo">
                                    <strong class="op-semibold">Danish Krone</strong>
                                </label>
                            </div>
                        </li>
                        <li>
                            <div class="custom-control custom-radio">
                                <input id="currency2" name="currency"  value="NOK" <?php echo $user['currency'] == "NOK" ? "checked": "" ?> class="custom-control-input"  type="radio">
                                <label for="currency2">
                                    <span class="custom-control-indicator"></span>
                                    <img src="<?php echo base_url('/public/images/currency1.png') ?>" alt="Country Logo">
                                    <strong class="op-semibold">Norwegian Krone</strong>
                                </label>
                            </div>
                        </li>
                        <li>
                            <div class="custom-control custom-radio">
                                <input id="currency3" name="currency"  value="SEK" <?php echo $user['currency'] == "SEK" ? "checked": "" ?> class="custom-control-input"  type="radio">
                                <label for="currency3">
                                    <span class="custom-control-indicator"></span>
                                    <img src="<?php echo base_url('/public/images/currency1.png') ?>" alt="Country Logo">
                                    <strong class="op-semibold">Swedish Krona</strong>
                                </label>
                            </div>
                        </li>
                        <li>
                            <div class="custom-control custom-radio">
                                <input id="currency4" name="currency"  value="EUR" <?php echo $user['currency'] == "EUR" ? "checked": "" ?> class="custom-control-input"  type="radio">
                                <label for="currency4">
                                    <span class="custom-control-indicator"></span>
                                    <img src="<?php echo base_url('/public/images/currency2.png') ?>" alt="Country Logo">
                                    <strong class="op-semibold">Euro</strong>
                                </label>
                            </div>
                        </li>
                    </ul>
                </div>
            </div> -->
            <!-- currency wrapper end -->

            <!-- Caption before section -->
            <div class="section-title section-border">
                <h3>Change Password</h3>
            </div>
            <!-- //Caption before section end -->

            <!-- change password wrapper -->
            <div class="change-password setting-section">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label class="labelTxt">Enter Old Password</label>
                            <div class="form-group-field">
                                <div class="input-wrapper">
                                    <input data-error="#old-password-error" type="password" maxlength="30" name="old_password" id="old-password" placeholder="********">
                                    <span class="fa fa-eye-slash absolute-postn password-toggle" data-state="hidden"></span>
                                </div>
                                <div class="error"><?php echo form_error("old_password") ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label class="labelTxt">Enter New Password</label>
                            <div class="form-group-field">
                                <div class="input-wrapper">
                                    <input data-error="#new-password-error" type="password" maxlength="30" name="new_password" id="new-password" placeholder="********">
                                    <span class="fa fa-eye-slash absolute-postn password-toggle" data-state="hidden"></span>
                                </div>
                                <div class="error"><?php echo form_error("new_password") ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label class="labelTxt">Enter Confirm Password</label>
                            <div class="form-group-field">
                                <div class="input-wrapper">
                                    <input data-error="#confirm-password-error" type="password" maxlength="30" name="confirm_password" id="confirm-password" placeholder="********">
                                    <span class="fa fa-eye-slash absolute-postn password-toggle" data-state="hidden"></span>
                                </div>
                                <div class="error"><?php echo form_error("confirm_password") ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- change password wrapper end -->

            <!-- button wrapper section -->
            <div class="button-wrapper">
                <input type="submit" value="Submit" class="custom-btn btn-margin btn-width save">
            </div>
            <!-- button wrapper section end -->
        </form>
    </div>
</div>