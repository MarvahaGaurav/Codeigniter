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
                <?php echo form_open('', array('id' => 'loginwebform', 'name' => "loginwebform")) ?>
                <h1 class="form-caption">Login</h1>
                <p class="form-desciption">Don't have an account? <a href="<?php echo base_url(); ?>web/index/signup" class="create-account">Create your account.</a></p> 
                <p class="form-desciption">
                    <?php echo isset($error) ? '<label class="alert-danger">' . $error . '</label>' : form_error('email', '<label class="alert-danger">', '</label>') ?>
                    <?php echo form_error('password', '<label class="alert-danger">', '</label>') ?>
                </p> 
                <div class="form-group">
                    <input type="email" class="form-control" name="email" placeholder="Email Address" required="required" autofocus="" />

                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="password" placeholder="Password" required="required"/>

                </div>
                <div class="form-group clearfix">
                    <label class="custom-control custom-checkbox pull-left">
                        <input type="checkbox" name="remember_me" id="remember_me" class="custom-control-input">
                        <span class="custom-control-indicator"></span>
                    </label>
                    <span class="remember">Remember me</span>
                    <label for="remember_me" class="pull-right">
                        <a href="<?php echo base_url(); ?>web/index/forgot" class="frgt-pwd">Forgot Password?</a>
                    </label>
                </div>
                <div class="form-group form-btn-wrap">
                    <button id="loginsubmitbtn" class="form-btn save" type="submit">Login</button>
                    <span class="continueasguest">Or continue as a <a href="<?php echo base_url(); ?>web/home">Guest</a></span>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>

    </div>
    <!-- ============== Login Section End ============== -->

</body>
