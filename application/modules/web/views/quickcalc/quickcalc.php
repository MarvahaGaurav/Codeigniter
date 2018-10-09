<div class="inner-container">
    <div class="container">

        <!-- breadcrumb -->
        <ul class="breadcrumb">
            <li><a href="javascript:void(0)">Home</a></li>
            <li><a href="<?php echo base_url('home/applications/' . $applicationId) ?>">Applications</a></li>
            <li><a href="<?php echo base_url('home/applications/' . $applicationId . '/rooms') ?>">Rooms</a></li>
            <li class="active">Fast Calc</li>
        </ul>
        <!-- //breadcrumb -->

        <div class="page-heading">
            <h1 class="page-title">Bathroom : Room Dimensions</h1>
            <p class="prj-description">We are continously designing, prototyping and testing new products to enable us to deliver products that are energy efficient and environmental friendly, in combination
                with a creation of the ambiance that you need, always keeping in mind that luminaires have a great impact on the environment, appearance and impression of the overall
                surroundings.</p>
        </div>

        <!-- Caption before section -->
        <div class="section-title clearfix">
            <h3>Room Dimensions</h3>
        </div>
        <!-- Caption before section -->

        <!-- form wrapper -->
        <div class="form-wrapper">
            <div class="row form-inline-wrapper">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label class="labelTxt">Room Reference</label>
                        <div class="form-group-field">
                            <input type="text" placeholder="Bathroom">
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label class="labelTxt">Room Length</label>
                        <div class="form-group-field form-dimention">
                            <input type="text" placeholder="10" value="">
                            <label class="field-type">
                                <select class="select-filed-name">
                                    <option>Meter</option>
                                    <option>Inch</option>
                                    <option>Yard</option>
                                </select>
                                <span class="customArrow"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label class="labelTxt">Room Breadth</label>
                        <div class="form-group-field form-dimention">
                            <input type="text" placeholder="8">
                            <label class="field-type">
                                <select class="select-filed-name">
                                    <option>Meter</option>
                                    <option>Inch</option>
                                    <option>Yard</option>
                                </select>
                                <span class="customArrow"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label class="labelTxt">Room Height</label>
                        <div class="form-group-field form-dimention">
                            <input type="text" placeholder="8">
                            <label class="field-type">
                                <select class="select-filed-name">
                                    <option>Meter</option>
                                    <option>Inch</option>
                                    <option>Yard</option>
                                </select>
                                <span class="customArrow"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label class="labelTxt">Working Plane Height</label>
                        <div class="form-group-field form-dimention">
                            <input type="text" placeholder="0.75">
                            <label class="field-type">
                                <select class="select-filed-name">
                                    <option>Meter</option>
                                    <option>Inch</option>
                                    <option>Yard</option>
                                </select>
                                <span class="customArrow"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label class="labelTxt">LUX Value</label>
                        <div class="form-group-field">
                            <select class="select-filed-name2">
                                <option>-NA-</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                            </select>
                            <span class="customArrow"></span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label class="labelTxt">Number of Luminaries</label>
                        <div class="form-group-field">
                            <select class="select-filed-name2">
                                <option>6</option>
                                <option>7</option>
                                <option>8</option>
                                <option>9</option>
                            </select>
                            <span class="customArrow"></span>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label class="labelTxt">Room Shape</label>
                        <div class="form-group-field">
                            <select class="select-filed-name2">
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                            </select>
                            <span class="customArrow"></span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label class="labelTxt">Select Product</label>
                        <div class="form-group-field">
                            <input id="uploadfile" class="select-filed-name2 chooseFile" type="text" placeholder="Choose File" disabled="display">
                            <label class="choosebtn">
                                <input type="file" id="uploadbtn">
                                <span>Choose</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label class="labelTxt">Pendant Length</label>
                        <div class="form-group-field form-dimention">
                            <input type="text" placeholder="1.75">
                            <label class="field-type">
                                <select class="select-filed-name">
                                    <option>Meter</option>
                                    <option>Inch</option>
                                    <option>Yard</option>
                                </select>
                                <span class="customArrow"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- //form wrapper -->

        <!-- button wrapper section -->
        <div class="section-title clearfix">
            <div class="button-wrapper">
                <input type="submit" value="Evaluate" class="custom-btn btn-margin btn-width save">
                <button type="button" class="custom-btn btn-margin btn-width cancel">Cancel</button>
            </div>
        </div>
        <!-- button wrapper section -->

        <!-- no record found -->
        <!-- <div class="no-record text-center">
            <img src="../images/no-found-note.png" alt="Note Paper">
            <p>You have no project.</p>
            <p>You have to <a href="login.html" class="login">Login</a> first to add a project!</p>
        </div> -->
        <!-- no record found -->

    </div>
</div>