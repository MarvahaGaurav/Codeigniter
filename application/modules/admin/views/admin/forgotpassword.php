
<body >
    <!--Login page  Wrap-->
    <div class="data-wrap">
        <!--COl Wrapper-->
        <div class="in-col-wrap clearfix">
            <!--Left Col-->
            <div class="in-left-col">
                <!--form inner col-->
                <div class="index-form-wrap">
                    <div class="form_hd">
                        <figure class="index-logo">
                            <img src="public/adminpanel/images/logo.png">
                        </figure>
                    </div>
                    <div class="form_inner_wrap">
                        <?php
                        if ($this->session->flashdata('Success') != '') {
                            echo $this->session->flashdata('Success');
                        }
                        ?>
                        <?php echo form_open('', array('id' => 'forget_pwd_admin_form')) ?>
                        <h1 class="index-comn-heading">Forgot Password  </h1>
                        <p class="index-note">Forgot your password? Don’t worry, Enter us your registered email and we will send you steps to reset your password.</p>
                        <div class="form-field-wrap" id="email_error">
                            <span class="ad-user"></span>
                            <input type="text" class="login_filed removespace" maxlength="150" placeholder="* Email Id" name="email" id="email" value="<?php echo set_value('email'); ?>"> 
                            <?php echo isset($error) ? '<label class="alert-danger">' . $error . '</label>' : form_error('email', '<label class="alert-danger">', '</label>'); ?>
                        </div>
                        <div class="form-field-wrap">
                            <div class="btn-wrapper">
                                <button class="index-comn-btn" onclick="window.location.href = '<?php echo base_url() . 'admin' ?>'"type="reset">Back </button>
                                <button style="float:right;" class="index-comn-btn" id="forgot" type="submit">Send </button>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
                <!--form inner col close-->
            </div>
            <!--Left Col-->
        </div>
        <!--COl Wrapper-->