<?php
    if ($this->session->flashdata('Success') != '') {
        echo $this->session->flashdata('Success');
    }
?>

<div class="form-section">
    <div class="form-inner-section">
        <div class="logo">
            <img src="public/images/logo.png" alt="logo">
        </div>
        <div class="form-wrapper">
            <div class="login-error">
                <span class="error"></span>
            </div>
            <?php echo form_open('', array('id' => 'forget_pwd_admin_form')) ?>
            <form>
                <h1 class="form-heading">Forgot Password</h1>
                <p class="form-desc">Forgot your password? Don’t worry, Enter us your registered email and we will send you steps to reset your password.</p>  
                <div class="form-group clearfix">
                    <span class="ad-password field-ico password-ico"></span>
                    <input type="text" class="form-field" maxlength="150" placeholder="* Email Id" name="email" id="email" value="<?php echo set_value('email'); ?>"> 
                    <?php echo isset($error) ? '<label class="alert-danger">' . $error . '</label>' : form_error('email', '<label class="alert-danger">', '</label>'); ?>
                </div>
                <div class="form-group text-center">
                    <button class="commn-btn cancel" onclick="window.location.href = '<?php echo base_url() . 'admin' ?>'"type="reset">Back </button>
                    <button class="commn-btn save" id="forgot" type="submit">Send </button>
                </div>
            </form>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>