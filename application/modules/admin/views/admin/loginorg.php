<body>
    <!--Login page  Wrap-->
    <div class="data-wrap">
        <!--COl Wrapper-->  
        <div class="in-col-wrap clearfix">
            <!--Left Col-->
            <div class="in-left-col">
                <?php
                if ($this->session->flashdata('message') != '') {
                    echo $this->session->flashdata('message');
                }
                ?>
                <!--form inner col-->              
                <div class="index-form-wrap">
                    <div class="form_hd">
                        <figure class="index-logo">
                            <img src="public/images/logo.png">
                        </figure>
                    </div>
                    <div class="form_inner_wrap">
                        <div class="login-error">
                            <span class="error"></span>
                        </div>
                        <?php echo form_open('', array('id' => 'login_admin_form')) ?>

                        <h1 class="index-comn-heading">Login  </h1>
                        <p class="index-note">Enter Your Details below to access your account </p>
                        <div class="form-field-wrap">
                            <span class="ad-user"></span>
                            <input type="text" class="login_filed removespace" maxlength="40" placeholder="* User Id" onfocus="this.removeAttribute('readonly');" readonly name="email" value="<?php echo isset($email) ? $email : set_value('email'); ?>"  autocomplete="off" /> 
                            <?php echo isset($error) ? '<label class="alert-danger">' . $error . '</label>' : form_error('email', '<label class="alert-danger">', '</label>') ?>


                        </div>
                        <div class="form-field-wrap" id="passworderr">
                            <span class="ad-password"></span>
                            <input type="password" class="login_filed removespace" maxlength="20" placeholder="* Password" onfocus="this.removeAttribute('readonly');" readonly name="password" value="<?php echo isset($password) ? $password : set_value('password'); ?>"  autocomplete="off" required />
                            <?php echo form_error('password', '<label class="alert-danger">', '</label>') ?>

                        </div>
                        <div class="form-field-wrap clearfix">
<!--                            <span class="rember-col">
                                <div class="th-checkbox">
                                    <input class="filter-type filled-in" type="checkbox" name="filter" id="flowers" value="flowers">
                                    <label for="flowers" class="lbl-check"><span></span>Remember me</label>
                                </div>  
                            </span>-->
                            <span class="forgot-pass">
                                <a class="" href="<?php echo base_url(); ?>admin/forgot">Forgot Password?</a>
                            </span>
                        </div>
                        <div class="form-field-wrap">
                            <div class="btn-wrapper">
                                <button class="index-comn-btn" type="submit" id="login">Login</button>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
                <!--form inner col close-->
            </div>
        </div>
    </div>

