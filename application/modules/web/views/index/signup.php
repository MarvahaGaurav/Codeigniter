<body class="form-backgournd">

    <div class="form-bg"></div>
    <!-- ============== Signup Section ============== -->
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
        <!-- //Right Cell -->

        <!-- ============== Left Section ============== -->
        <div class="fm-table-cell fm-cell tbl-cell-1">
            <div class="form-wrapper">
                <form>
                    <h1 class="form-caption">Signup</h1>
                    <p class="form-desciption">Have an account? <a href="<?php echo base_url(); ?>web/index/index" class="create-account">Login Now</a></p>

                    <!-- thumb upload -->
                    <div class="image-wrapper">
                        <div class="image-view-box img-view130p img-viewbdr-radius">
                            <div id="image-view" class="image-view img-view130" style="background-image:url(public/images/user.png);"></div>                                    
                        </div>
                        <div class="upload-btn">
                            <input type="file" name="image" id="upload">
                            <img src="public/images/camera.svg" />
                        </div>
                    </div>
                    <!-- thumb upload -->

                    <!-- Business User -->
                    <div class="business">
                        <div class="form-group-inline clearfix">
                            <div class="form-group">
                                <select>
                                    <option value="business">Business</option>
                                    <option value="private">Private</option>
                                    <option value="installer">Installer</option>
                                    <option value="wholesaler">Wholesaler</option>
                                </select>
                                <span class="fs-caret"></span>
                            </div>
                        </div>

                        <div class="form-group-inline clearfix">
                            <div class="form-group">
                                <input type="text" class="form-control" name="username" placeholder="Full Name" required="" autofocus="" />
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control" name="username" placeholder="Email Address" required="" autofocus="" />
                            </div>
                        </div>

                        <div class="form-group-inline clearfix">
                            <div class="form-group">
                                <input type="password" class="form-control" name="password" placeholder="Password" required=""/>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" name="password" placeholder="Confirm Password" required=""/>
                            </div>
                        </div>

                        <div class="form-group-inline clearfix">
                            <div class="form-group">
                                <input type="number" class="form-control" name="username" placeholder="Contact Number" required="" autofocus="" />
                            </div>
                            <div class="form-group">
                                <input type="number" class="form-control" name="password" placeholder="Alternate Number" required=""/>
                            </div>
                        </div>

                        <!-- wholesaler -->
                        <div class="wholesaler-field">
                            <div class="form-group-inline clearfix">
                                <div class="form-group">
                                    <input type="number" class="form-control" placeholder="Company Registration Number" />
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Company Name" required=""/>
                                </div>
                            </div>

                            <div class="form-group clearfix">
                                <div class="chooseFile">
                                    <input id="uploadfile" class="form-control" placeholder="Choose File" disabled="display">
                                    <div class="uploadfile-wrap">
                                        <input type="file" id="uploadbtn">
                                        <span>Browse</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- wholesaler end -->

                        <!-- installer -->
                        <div class="installer-field">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Company Name" />
                            </div>
                        </div>
                        <!-- installer end -->

                        <div class="form-group-inline clearfix">
                            <div class="form-group">
                                <select>
                                    <option>Country</option>
                                    <option>India</option>
                                    <option>America</option>
                                </select>
                                <span class="fs-caret"></span>
                            </div>
                            <div class="form-group">
                                <select>
                                    <option>City</option>
                                    <option>India</option>
                                    <option>America</option>
                                </select>
                                <span class="fs-caret"></span>
                            </div>
                        </div>

                        <div class="form-group-inline clearfix">
                            <div class="form-group no-margin">
                                <input type="number" class="form-control" name="username" placeholder="Zipcode" required="" autofocus="" />
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-btn-wrap">
                        <button class="form-btn save" type="submit">Signup</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- //Left Cell -->

    </div>
    <!-- ============== Login Section End ============== -->

    <script>

        // upload the image on click
        $('#upload').change(function(){
            var file = this.files[0];
            var reader = new FileReader();
            reader.onloadend = function () {
            $('#image-view').css('background-image', 'url("' + reader.result + '")');
            }
            if (file) {
                reader.readAsDataURL(file);
            } else {
                console.log('not done');
            }
        });

        $(document).ready(function(){

            //show pop up on from select options
            $('select').change(function () {
                if ($(this).val() == "business") {
                    $('.wholesaler-field').css('display','none');
                }
            });

            //show pop up on from select options
            $('select').change(function () {
                if ($(this).val() == "wholesaler") {
                    $('.wholesaler-field').css('display','block');
                    $('.installer-field').css('display','none');
                }
            });

            //show pop up on from select options
            $('select').change(function () {
                if ($(this).val() == "installer") {
                    $('.installer-field').css('display','block');
                    $('.wholesaler-field').css('display','none');
                }
            });
            
        })

    </script>

</body>