<div class="form-section">
    <div class="form-inner-section">
        <div class="logo">
            <h1>ArchiveZ </h1>
        </div>
        <div class="form-wrapper">
            <div class="login-error">
                <span class="error"></span>
            </div>
            <form method="post" id="resetform">
                <input type="hidden" name="<?php echo $csrfName; ?>" id="<?php echo $csrfName; ?>" value="<?php echo $csrfToken; ?>"> 
                <h1 class="form-heading">Reset Passwor</h1>
                <p class="form-desc"></p>  
                <div class="form-group" id="passerror">
                    <span class="field-ico password-ico"></span>
                    <input type="password" class="form-field" maxlength="40" placeholder="* Enter New Password" id="password" name="password"  autocomplete="off"> 
                    <span class="error-mssg passwordErr" id="password" ></span>
                    <span class="bar"></span>
                </div>
                <div class="form-group" id="conpassreq">
                    <span class="field-ico password-ico"></span>
                    <input type="password" class="form-field" maxlength="40" placeholder="* Enter Confirm Password" id="cnfpassword" name="cpassword"  autocomplete="off"> 
                    <span class="error-mssg cnfpasswordErr"  id="cnfpassword"></span>
                    <span class="bar"></span>
                </div>
                <div class="form-group text-center">
                    <button class="commn-btn save" onclick="return validatepassword()" type="submit" id="resetbtn">Send</button>
                </div>
            </form>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
