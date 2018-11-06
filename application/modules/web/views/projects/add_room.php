<div class="inner-container">
    <div class="container">

        <!-- breadcrumb -->
        <ul class="breadcrumb">
            <li><a href="javascript:void(0)">Home</a></li>
            <li><a href="javascript:void(0)">Project</a></li>
            <li><a href="javascript:void(0)">Create New Project</a></li>
            <li><a href="javascript:void(0)">Select Application</a></li>
            <li><a href="javascript:void(0)">Rooms</a></li>
            <li><a href="javascript:void(0)">Room Type</a></li>
            <li class="active">Room Dimensions</li>
        </ul>
        <!-- //breadcrumb -->

        <div class="page-heading">
            <h1 class="page-title"><?php echo $room['title']; ?> : Room Dimensions</h1>
            <p class="prj-description">We are continously designing, prototyping and testing new products to enable us to deliver products that are energy efficient and environmental friendly, in combination
                with a creation of the ambiance that you need, always keeping in mind that luminaires have a great impact on the environment, appearance and impression of the overall
                surroundings.</p>
        </div>

        <!-- Caption before section -->
        <div class="section-title clearfix">
            <h3>Room Dimensions</h3>
        </div>

        <!-- Caption before section -->
        <?php echo form_open_multipart(base_url("home/projects/create_room"), array ('id' => 'add_room_form', 'name' => "add_room_form")) ?>
        <!-- form wrapper -->
        <div class="form-wrapper">
            <div class="row form-inline-wrapper">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label class="labelTxt">Room Reference</label>
                        <div class="form-group-field">
                            <input readonly="" name="room_refrence" id="room_refrence" type="text" placeholder="Bathroom" value="<?php echo $room['title']; ?>">
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label class="labelTxt">Room Length</label>
                        <div class="form-group-field form-dimention">
                            <input class="is_number" type="text" placeholder="10" name="room_lenght" id="room_lenght" value="<?php echo isset($cookie_data['room_lenght'])?$cookie_data['room_lenght']:''; ?>">
                            <label class="field-type">
                                <select name="room_lenght_unit" id="room_lenght_unit" class="select-filed-name">
                                    <?php
                                    foreach ($units as $unit) {
                                        $selected = (isset($cookie_data['room_lenght_unit'])?$cookie_data['room_lenght_unit']:'' == $unit) ? " selected " : '';
                                        echo "<option $selected value='" . $unit . "'>$unit</option>";
                                    }
                                    ?>
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
                            <input class="is_number"  type="text" placeholder="8" name="room_breadth" id="room_breadth" value="<?php echo isset($cookie_data['room_breadth'])?$cookie_data['room_breadth']:''; ?>">
                            <label class="field-type">
                                <select class="select-filed-name" name="room_breadth_unit" id="room_breadth_unit">
                                    <?php
                                    foreach ($units as $unit) {
                                        $selected = (isset($cookie_data['room_breadth_unit'])?$cookie_data['room_breadth_unit']:'' == $unit) ? " selected " : '';
                                        echo "<option $selected value='" . $unit . "'>$unit</option>";
                                    }
                                    ?>
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
                            <input class="is_number"  type="text" placeholder="8" name="room_height" id="room_height" value="<?php echo isset($cookie_data['room_height'])?$cookie_data['room_height']:''; ?>">
                            <label class="field-type">
                                <select class="select-filed-name" name="room_height_unit" id="room_height_unit">
                                    <?php
                                    foreach ($units as $unit) {
                                        $selected = (isset($cookie_data['room_height_unit'])?$cookie_data['room_height_unit']:'' == $unit) ? " selected " : '';
                                        echo "<option $selected value='" . $unit . "'>$unit</option>";
                                    }
                                    ?>
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
                            <input class="is_number"  type="text" placeholder="0.75"  name="room_plane_height" id="room_plane_height" value="<?php echo isset($cookie_data['room_plane_height'])?$cookie_data['room_plane_height']:''; ?>">
                            <label class="field-type">
                                <select class="select-filed-name"  name="room_plane_height_unit" id="room_plane_height_unit">
                                    <?php
                                    foreach ($units as $unit) {
                                        $selected = (isset($cookie_data['room_plane_height_unit'])?$cookie_data['room_plane_height_unit']:'' == $unit) ? " selected " : '';
                                        echo "<option $selected value='" . $unit . "'>$unit</option>";
                                    }
                                    ?>
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
                            <input class="is_number"  type="text" placeholder="8" name="room_luminaries_x" id="room_luminaries_x" value="<?php echo isset($cookie_data['room_luminaries_x'])?$cookie_data['room_luminaries_x']:''; ?>">
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
                            <input class="is_number"  type="text" placeholder="8" name="room_luminaries_y" id="room_luminaries_y" value="<?php echo isset($cookie_data['room_luminaries_y'])?$cookie_data['room_luminaries_y']:''; ?>">
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
                <div class="clearfix"></div>
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
                            <input id="uploadfile" class="select-filed-name2 chooseFile" type="text" placeholder="Choose File" disabled="display" value="<?php echo isset($selectd_room['product_name'])?$selectd_room['product_name']:''; ?>">
                            <label class="choosebtn">
                                <a name="choose_product" id="choose_product">Choose</a>
                            </label>
                        </div>
                    </div>
                </div>
                <?php
                if ('' != $mounting_type and ( 1 == $mounting_type || 6 == $mounting_type)) {
                    ?>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label class="labelTxt">Pendant Length</label>
                            <div class="form-group-field form-dimention">
                                <input value="<?php echo isset($cookie_data['room_pendant_length'])?$cookie_data['room_pendant_length']:''; ?>" class="is_number" type="text" placeholder="1.75" name="room_pendant_length" id="room_pendant_length">
                                <label class="field-type">
                                    <select class="select-filed-name"  name="room_pendant_length_unit" id="room_pendant_length_unit">
                                        <?php
                                        foreach ($units as $unit) {
                                            $selected = (isset($cookie_data['room_pendant_length_unit'])?$cookie_data['room_pendant_length_unit']:'' == $unit) ? " selected " : '';
                                            echo "<option $selected value='" . $unit . "'>$unit</option>";
                                        }
                                        ?>
                                    </select>
                                    <span class="customArrow"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>

            <?php if (0) { ?>
                <div class="row form-inline-wrapper">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label class="labelTxt">Lux Value</label>
                            <div class="form-group-field form-dimention">
                                <input disabled="true" type="text" placeholder="1.75" value="<?php echo $room['lux_values']; ?>">
                                <label class="field-type">
                                    <select class="select-filed-name">
                                        <option>Lux</option>
                                    </select>
                                    <span class="customArrow"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                        <div class="form-group">
                            <label class="labelTxt">Maintenance Factor</label>
                            <div class="form-group-field form-dimention">
                                <input disabled="true" type="text" placeholder="1.75" value="<?php echo $room['maintainance_factor']; ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row form-inline-wrapper">
                    <fieldset>
                        <legend>Reflection</legend>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label class="labelTxt">Ceiling</label>
                                <div class="form-group-field form-dimention">
                                    <input readonly="true" disabled="true" type="text" placeholder="1.75" value="<?php echo $room['reflection_values_ceiling']; ?>">
                                    <label class="field-type">
                                        <select class="select-filed-name">
                                            <option>%</option>
                                        </select>
                                        <span class="customArrow"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label class="labelTxt">Walls</label>
                                <div class="form-group-field form-dimention">
                                    <input readonly="true" disabled="true" type="text" placeholder="1.75" value="<?php echo $room['reflection_values_wall']; ?>">
                                    <label class="field-type">
                                        <select class="select-filed-name">
                                            <option>%</option>
                                        </select>
                                        <span class="customArrow"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label class="labelTxt">Floor</label>
                                <div class="form-group-field form-dimention">
                                    <input readonly="true" disabled="true" type="text" placeholder="1.75" value="<?php echo $room['reflection_values_floor']; ?>">
                                    <label class="field-type">
                                        <select class="select-filed-name">
                                            <option>%</option>
                                        </select>
                                        <span class="customArrow"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
            <?php } ?>
        </div>

        <!-- //form wrapper -->
        <?php $disabled = (isset($selectd_room['product_name']) and '' != $selectd_room['product_name']) ? " " : " disabled "; ?>
        <!-- button wrapper section -->
        <div class="section-title clearfix">
            <div class="button-wrapper">
                <input <?php echo $disabled; ?> type="submit" value="Done" class="custom-btn btn-margin btn-width save">
                <button type="button" class="custom-btn btn-margin btn-width cancel">Cancel</button>
            </div>
        </div>
        <!-- button wrapper section -->
        <input type="hidden" name="room_id" id="room_id" value="<?php echo $room_id; ?>">
        <input type="hidden" name="application_id" id="application_id" value="<?php echo $application_id; ?>">
        <input type="hidden" name="article_code" id="article_code" value="<?php echo $selectd_room['articel_id']; ?>">
        <input type="hidden" name="type" id="type" value="<?php echo $selectd_room['type']; ?>">
        <input type="hidden" name="product_id" id="product_id" value="<?php echo $selectd_room['product_id']; ?>">
        <?php echo form_close(); ?>
        <!-- no record found -->
        <!-- <div class="no-record text-center">
            <img src="../../images/no-found-note.png" alt="Note Paper">
            <p>You have no project.</p>
            <p>You have to <a href="login.html" class="login">Login</a> first to add a project!</p>
        </div> -->
        <!-- no record found -->

    </div>
</div>