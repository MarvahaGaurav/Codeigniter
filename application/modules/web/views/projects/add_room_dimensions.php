<div class="inner-container">
    <div class="container">

        <!-- breadcrumb -->
        <ul class="breadcrumb">
            <li><a href="javascript:void(0)">Home</a></li>
            <li><a href="javascript:void(0)">Projects</a></li>
            <li><a href="javascript:void(0)">Create New Project</a></li>
            <li><a href="javascript:void(0)">Levels</a></li>
            <li><a href="javascript:void(0)">Rooms</a></li>
            <li><a href="javascript:void(0)">Applications</a></li>
            <li><a href="javascript:void(0)">Room Type</a></li>
            <li class="active">Room Dimensions</li>
        </ul>
        <!-- //breadcrumb -->

        <div class="page-heading">
            <h1 class="page-title">Bathroom : Room Dimensions</h1>
            <p class="prj-description">We are continously designing, prototyping and testing new products to enable us
                to deliver products that are energy efficient and environmental friendly, in combination
                with a creation of the ambiance that you need, always keeping in mind that luminaires have a great
                impact on the environment, appearance and impression of the overall
                surroundings.</p>
        </div>

        <!-- Caption before section -->
        <div class="section-title clearfix">
            <h3>Room Dimensions</h3>
        </div>
        <!-- Caption before section -->

        <?php echo form_open_multipart(base_url(uri_string()), array ('id' => 'quick_cal_form', 'name' => "quick_cal_form")) ?>

        <!-- form wrapper -->
        <div class="form-wrapper">
            <div class="row form-inline-wrapper">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label class="labelTxt">Room Reference</label>
                        <div class="form-group-field">
                            <input type="text" name="name" placeholder="Bathroom" value="<?php echo $room['title'] ?>" readonly>
                            <input type="hidden" name="room_id" <?php echo $room['room_id'] ?>>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label class="labelTxt">Room Length</label>
                        <div class="form-group-field form-dimention">
                            <input type="text" placeholder="10" name="length">
                            <label class="field-type">
                                <select class="select-filed-name" name="length_unit">
                                    <?php foreach ($units as $unit) : ?>
                                    <option value="<?php echo strtolower($unit) ?>"><?php echo $unit ?></option>
                                    <?php endforeach ?>
                                </select>
                                <span class="customArrow"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label class="labelTxt">Room Width</label>
                        <div class="form-group-field form-dimention">
                            <input type="text" placeholder="8" name="width">
                            <label class="field-type">
                                <select class="select-filed-name" name="width_unit">
                                    <?php foreach ($units as $unit) : ?>
                                    <option value="<?php echo strtolower($unit) ?>"><?php echo $unit ?></option>
                                    <?php endforeach ?>
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
                            <input type="text" placeholder="8" name="height">
                            <label class="field-type">
                                <select class="select-filed-name" name="height_unit">
                                    <?php foreach ($units as $unit) : ?>
                                    <option value="<?php echo strtolower($unit) ?>"><?php echo $unit ?></option>
                                    <?php endforeach ?>
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
                            <input class="is_number"  type="text" placeholder=""  name="room_plane_height" id="room_plane_height" value="<?php echo $room['reference_height'] ?>">
                            <label class="field-type">
                                <select class="select-filed-name"  name="room_plane_height_unit" id="room_plane_height_unit">
                                    <?php foreach ($units as $unit) : ?>
                                    <option value="<?php echo strtolower($unit) ?>"><?php echo $unit ?></option>
                                    <?php endforeach ?>
                                </select>
                                <span class="customArrow"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <div class="form-group">
                        <label class="labelTxt">Number of Luminaries</label>
                        <div class="form-group-field form-dimention">
                            <input class="is_number"  type="text" placeholder="8" name="room_luminaries_x" id="room_luminaries_x" value="">
                            <label class="field-type">
                                <select class="select-filed-name">
                                    <option>X</option>
                                </select>
                                <span class="customArrow"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <div class="form-group">
                        <label class="labelTxt"> .</label>
                        <div class="form-group-field form-dimention">
                            <input class="is_number"  type="text" placeholder="8" name="room_luminaries_y" id="room_luminaries_y" value="">
                            <label class="field-type">
                                <select class="select-filed-name">
                                    <option>Y</option>
                                </select>
                                <span class="customArrow"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                    <div class="form-group">
                        <label class="labelTxt">Total</label>
                        <div class="form-group-field">
                            <input disabled="true" readonly="true" type="text" name="xy_total" id="xy_total">
                            <span name="xy_total_error" id="xy_total_error"></span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label class="labelTxt">Room Shape</label>
                        <div class="form-group-field">
                            <select class="select-filed-name2" name="room_shape" id="room_shape">
                                <option value="rectangular">Rectangular</option>
                            </select>
                            <span class="customArrow"></span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label class="labelTxt">Select Product</label>
                        <div class="form-group-field">
                            <input id="uploadfile" class="select-filed-name2 chooseFile" type="text" placeholder="Choose File" disabled="display" value="<?php echo '' ?>">
                            <label class="choosebtn">
                                <a name="choose_product" id="choose_product">Choose</a>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 concealable">
                    <div class="form-group">
                        <label class="labelTxt">Pendant Length</label>
                        <div class="form-group-field form-dimention">
                            <input type="text" placeholder="">
                            <label class="field-type">
                                <select class="select-filed-name" name="pendant_length_unit">
                                    <?php foreach ($units as $unit) : ?>
                                    <option value="<?php echo strtolower($unit) ?>"><?php echo $unit ?></option>
                                    <?php endforeach ?>
                                </select>
                                <span class="customArrow"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="section-title clearfix">
            <h3>Advanced Options&nbsp;&nbsp;<span class="caret clickable" data-toggle="collapse" data-target="#advanced-options-div"></span></h3>
        </div>
        <div class="form-wrapper collapse" id="advanced-options-div">
            <div class="row form-inline-wrapper">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label class="labelTxt">Reflection Values</label>
                        <div class="form-group-field">
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label class="labelTxt">Wall</label>
                        <div class="form-group-field">
                            <input type="text" name="rho_wall" value="<?php echo $room['reflection_values_wall'] ?>" placeholder="">
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label class="labelTxt">Ceiling</label>
                        <div class="form-group-field">
                            <input type="text" name="rho_ceiling" value="<?php echo $room['reflection_values_ceiling'] ?>" placeholder="">
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label class="labelTxt">Floor</label>
                        <div class="form-group-field">
                            <input type="text" name="rho_floor" value="<?php echo $room['reflection_values_floor'] ?>" placeholder="">
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label class="labelTxt">Maintainance Factor</label>
                        <div class="form-group-field">
                            <input type="text" name="rho_floor" value="<?php echo $room['maintainance_factor'] ?>" placeholder="">
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label class="labelTxt">Lux Value</label>
                        <div class="form-group-field">
                            <input type="text" name="name" placeholder="Bathroom" value="<?php echo $room['lux_values'] ?>">
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
            
        <!-- //form wrapper -->

        <!-- button wrapper section -->
        <div class="section-title clearfix">
            <div class="button-wrapper">
                <input type="submit" value="Done" class="custom-btn btn-margin btn-width save">
                <button type="button" class="custom-btn btn-margin btn-width cancel">Cancel</button>
            </div>
        </div>

        <input type="hidden" name="project_id" id="project_id" value="<?php echo $projectId; ?>">
        <input type="hidden" name="room_id" id="room_id" value="<?php echo $room_id; ?>">
        <input type="hidden" name="level" id="level" value="<?php echo $level; ?>">
        <input type="hidden" name="application_id" id="application_id" value="<?php echo $application_id; ?>">
        <input type="hidden" name="article_code" id="room_id" value="<?php echo isset($selectd_room['articel_id'])?$selectd_room['articel_id']:''; ?>">
        <input type="hidden" name="type" id="room_id" value="<?php echo isset($selectd_room['type'])?$selectd_room['type']:''; ?>">
        <input type="hidden" name="product_id" id="application_id" value="<?php echo isset($selectd_room['product_id'])?$selectd_room['product_id']:''; ?>">
        <?php echo form_close(); ?>
        <!-- button wrapper section -->

        <!-- no record found -->
        <!-- <div class="no-record text-center">
                    <img src="../../images/no-found-note.png" alt="Note Paper">
                    <p>You have no project.</p>
                    <p>You have to <a href="login.html" class="login">Login</a> first to add a project!</p>
                </div> -->
        <!-- no record found -->

    </div>
</div>