<body class="form-backgournd">

    <div class="form-bg"></div>
    <!-- ============== Login Section ============== -->
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

        <!-- ============== Left Section ============== -->
        <div class="fm-table-cell fm-cell tbl-cell-1">
            <div class="form-wrapper">
                <?php echo form_open("web/index/resetpassword?token=".$token, array('id' => 'resetwebform', 'name' => "resetwebform")) ?>
                    <h1 class="form-caption">Reset Password</h1>
                    <p class="form-desciption">You can reset your password from here</p> 
                    <div class="form-group">
                        <input type="password" class="form-control" name="password" placeholder="New Password" value="<?php echo set_value('password'); ?>" required="required" autofocus="" />
                        <?php echo form_error('password', '<label class="alert-danger">', '</label>'); ?>
                    </div> 
                    <div class="form-group">
                        <input type="password" class="form-control" name="cnfpassword" placeholder="Confirm Password" value="<?php echo set_value('cnfpassword'); ?>" required="required" autofocus="" />
                        <?php echo form_error('cnfpassword', '<label class="alert-danger">', '</label>'); ?>
                    </div>
                    <div class="form-group form-btn-wrap">
                        <button class="form-btn save" type="submit">Save</button>
                    </div>
                <?php echo form_close(); ?>
            </div>
        </div>

    </div>
    <!-- ============== Login Section End ============== -->

</body>