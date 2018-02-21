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
                <p>SG Lighting has vast experience of and expertise in a wide range of different types of projects such as indoor &amp; outdoor lighting.</p>
            </div>
        </div>

        <!-- ============== Left Section ============== -->
        <div class="fm-table-cell fm-cell tbl-cell-1">
            <div class="form-wrapper">
                <?php echo form_open('', array('id' => 'loginwebform', 'name' => "loginwebform")) ?>
                    <h1 class="form-caption">Forgot Password</h1>
                    <p class="form-desciption">Forgot your password? Enter the email address of your account to reset your password.</p> 
                    <p class="form-desciption">
                    <?php echo isset($error) ? '<label class="alert-danger">' . $error . '</label>' : form_error('email', '<label class="alert-danger">', '</label>') ?>                    
                    </p> 
                    <div class="form-group">
                        <input type="email" class="form-control" name="email" placeholder="Email Address" required="required" autofocus="">
                    </div>
                    <div class="form-group form-btn-wrap">
                        <button class="form-btn save" type="submit">Send</button>
                    </div>
                <?php echo form_close(); ?>
            </div>
        </div>

    </div>
    <!-- ============== Login Section End ============== -->


</body>