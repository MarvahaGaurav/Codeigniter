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
                <?php echo form_open('', array('id' => 'resetwebform', 'name' => "resetwebform")) ?>
                    <h1 class="form-caption">Set Password</h1>
                    <p class="form-desciption">Lorem ipsum dolor sit amet, consectetuer adipiscing elit,  sed diam nunoy.</p> 
                    <div class="form-group">
                        <input type="email" class="form-control" name="username" placeholder="New Password" required="" autofocus="" />
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control" name="username" placeholder="Confirm Password" required="" autofocus="" />
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