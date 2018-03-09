<?php
    if ($this->session->flashdata('Success') != '') {
        echo $this->session->flashdata('Success');
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
            <?php echo form_open('', array('id' => 'forget-password-form','onsubmit'=>'return submitfrmforgot()')) ?>
            <form>
                <h1 class="form-heading">Forgot Password</h1>
                <p class="form-desc">Forgot your password? Don’t worry, Enter us your registered email and we will send you steps to reset your password.</p>  
                <div class="form-group clearfix">
                    <span class="field-ico user-ico"></span>
                    <input type="text" class="form-field" maxlength="150" placeholder="* Email Id" name="email" id="forgot-password-field" value="<?php echo set_value('email'); ?>"> 
                    <?php echo isset($error) ? '<label class="alert-danger">' . $error . '</label>' : form_error('email', '<label class="alert-danger">', '</label>'); ?>
                </div>
                <div class="form-group text-center">
                    <button class="commn-btn cancel" onclick="window.location.href = '<?php echo base_url() . 'admin' ?>'"type="reset">Back </button>
                    <button class="commn-btn save" id="forgot-password-btn" type="submit">Send </button>
                </div>
            </form>
            <?php echo form_close(); ?>
        </div>
    </div>
    <script>
        /* function submitfrmforgot(){
            <?php if(isset($error) && !empty($error)){ ?>
                $('#forgot').removeAttr('disabled','');
            <?php }else{ ?>
                $('#forgot').attr('disabled','disabled');
            <?php } ?>
        }; */
    </script>
</div>