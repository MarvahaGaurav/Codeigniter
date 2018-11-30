<?php
if ($this->session->flashdata('message') != '') {
    echo $this->session->flashdata('message');
}
?>

<div class="fm-table-section">

    <div class="fm-table-cell tbl-cell-1">
        <div class="logo">
            <img src="public/images/logo.png" alt="logo">
            <p class="text-center logo-txt">Smart Guide</p>
        </div>
    </div>

    <div class="fm-table-cell tbl-cell-2">
        <div class="form-wrapper">
                <div class="login-error">
                    <span class="error"></span>
                </div>
                <?php echo form_open('', array('id' => 'login_admin_form')) ?>
                <form>
                    <h1 class="form-heading">Login</h1>
                    <p class="form-desc">Enter Your Details below to access your account</p>  
                    <div class="form-group">
                        <span class="field-ico user-ico"></span>
                        <input type="text" class="form-field" maxlength="40" placeholder="* User Id" onfocus="this.removeAttribute('readonly');" readonly name="email" value="<?php echo isset($email) ? $email : set_value('email'); ?>"  autocomplete="off" /> 
                        <?php echo isset($error) ? '<label class="alert-danger">' . $error . '</label>' : form_error('email', '<label class="alert-danger">', '</label>') ?>
                    </div>
                    <div class="form-group" id="passworderr">
                        <span class="field-ico password-ico"></span>
                        <input type="password" class="form-field" maxlength="20" placeholder="* Password" onfocus="this.removeAttribute('readonly');" readonly name="password" value="<?php echo isset($password) ? $password : set_value('password'); ?>"  autocomplete="off" required />
                        <?php echo form_error('password', '<label class="alert-danger">', '</label>') ?>
                    </div>
                    <div class="form-group clearfix">
                        <label class="pull-right">
                            <a href="<?php echo base_url(); ?>admin/forgot" class="frgt-pwd">Forgot Password?</a>
                        </label>
                    </div>
                    <div class="form-group text-center">
                        <button class="commn-btn save" type="submit" id="login">Login</button>
                    </div>
                </form>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
    
</div>