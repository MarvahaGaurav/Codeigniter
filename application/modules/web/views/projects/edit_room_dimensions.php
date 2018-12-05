<div class="inner-container">
    <div class="container">

        <!-- breadcrumb -->
        <ul class="breadcrumb">
            <li><a href="javascript:void(0)">Home</a></li>
            <li><a href="<?php echo base_url('home/projects') ?>">Projects</a></li>
            <li><a href="<?php echo base_url('home/projects/' . $projectId) ?>">Details</a></li>
            <li><a href="<?php echo base_url('home/projects/' . $projectId . '/levels') ?>">Levels</a></li>
            <li><a href="<?php echo base_url('home/projects/' . $projectId . '/levels/' . $level . '/rooms') ?>">Rooms</a></li>
            <li class="active">Edit Room Dimensions</li>
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

        <?php echo form_open_multipart(base_url(uri_string()), array ('id' => 'edit_room_form', 'name' => "edit_room_form")) ?>

        <!-- form wrapper -->
        <div class="form-wrapper">
            <div class="row form-inline-wrapper">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label class="labelTxt">Room Reference</label>
                        <div class="form-group-field">
                            <input type="text" name="name" placeholder="Bathroom" value="<?php echo isset($cookie_data['name'])?$cookie_data['name']:$room['name'] ?>" readonly>
                            <label for="name" id="name-error" class="error"><?php echo form_error('name') ?></label>
                            <input type="hidden" name="room_id" value="<?php echo encryptDecrypt($room['room_id']) ?>">
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label class="labelTxt">Room Reference Number</label>
                        <div class="form-group-field">
                            <input class="alphanum-only-field restrict-characters" data-restrict-to="25" type="text" name="reference_number" placeholder="" value="<?php echo isset($cookie_data['reference_number'])?$cookie_data['reference_number']:$room['reference_number'] ?>">
                            <label for="name" id="name-error" class="error"><?php echo form_error('name') ?></label>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label class="labelTxt">Room Reference Name</label>
                        <div class="form-group-field">
                            <input class="alphanumspaces-only-field restrict-characters" data-restrict-to="50" type="text" name="reference_name" placeholder="" value="<?php echo isset($cookie_data['reference_name'])?$cookie_data['reference_name']:$room['reference_name'] ?>">
                            <label for="name" id="name-error" class="error"><?php echo form_error('name') ?></label>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label class="labelTxt">Room Length</label>
                        <div class="form-group-field form-dimention">
                            <input class="dialux-suggestions-fields number-only-field restrict-character" data-restrict-to="15" type="text" placeholder="10" name="length" value="<?php echo isset($cookie_data['length'])?$cookie_data['length']:$room['length'] ?>">
                            <label for="length" id="length-error" class="error"><?php echo form_error('length') ?></label>
                            <label class="field-type">
                                <select class="dialux-suggestions-fields select-filed-name room-dimension-units" name="length_unit">
                                    <?php foreach ($units as $unit) : ?>
                                    <option value="<?php echo strtolower($unit) ?>" <?php echo isset($cookie_data['length_unit']) && $cookie_data['length_unit'] === strtolower($unit)?"selected":"" ?>><?php echo $unit ?></option>
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
                            <input class="dialux-suggestions-fields number-only-field restrict-character" data-restrict-to="15" type="text" placeholder="8" name="width" value="<?php echo isset($cookie_data['width'])?$cookie_data['width']:$room['width'] ?>">
                            <label for="width" id="width-error" class="error"><?php echo form_error('width') ?></label>
                            <label class="field-type">
                                <select class="dialux-suggestions-fields select-filed-name room-dimension-units" name="width_unit">
                                    <?php foreach ($units as $unit) : ?>
                                    <option value="<?php echo strtolower($unit) ?>" <?php echo isset($cookie_data['width_unit']) && $cookie_data['width_unit'] === strtolower($unit)?"selected":"" ?>><?php echo $unit ?></option>
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
                            <input class="dialux-suggestions-fields number-only-field restrict-character" data-restrict-to="15" type="text" placeholder="8" name="height" value="<?php echo isset($cookie_data['height'])?$cookie_data['height']:$room['height'] ?>">
                            <label for="height" id="height-error" class="error"><?php echo form_error('height') ?></label>
                            <label class="field-type">
                                <select class="dialux-suggestions-fields select-filed-name room-dimension-units" name="height_unit">
                                    <?php foreach ($units as $unit) : ?>
                                    <option value="<?php echo strtolower($unit) ?>" <?php echo isset($cookie_data['height_unit']) && $cookie_data['height_unit'] === strtolower($unit)?"selected":"" ?>><?php echo $unit ?></option>
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
                            <input class="dialux-suggestions-fields number-only-field restrict-character" data-restrict-to="15" class="is_number"  type="text" placeholder=""  name="room_plane_height" id="room_plane_height" value="<?php echo isset($cookie_data['room_plane_height'])?$cookie_data['room_plane_height']:$room['working_plane_height']*100 ?>">
                            <label for="room_plane_height" id="room_plane_height-error" class="error"><?php echo form_error('room_plane_height') ?></label>
                            <label class="field-type">
                                <select class="select-filed-name"  name="room_plane_height_unit" id="room_plane_height_unit">
                                    <option value="cms">Cm</option>
                                </select>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <div class="form-group">
                        <label class="labelTxt">Number of Luminaries</label>
                        <div class="form-group-field form-dimention">
                            <input class="is_number number-only-field restrict-character" data-restrict-to="15"  type="text" placeholder="8" name="room_luminaries_x" id="room_luminaries_x" value="<?php echo isset($cookie_data['room_luminaries_x'])?$cookie_data['room_luminaries_x']:$room['luminaries_count_x'] ?>">
                            <label for="room_luminaries_x" id="room_luminaries_x-error" class="error"><?php echo form_error('room_luminaries_x') ?></label>
                            <label class="field-type">
                                <select class="select-filed-name">
                                    <option>X</option>
                                </select>
                                <!-- <span class="customArrow"></span> -->
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <div class="form-group">
                        <label class="labelTxt"> .</label>
                        <div class="form-group-field form-dimention">
                            <input class="is_number number-only-field restrict-character" data-restrict-to="15"  type="text" placeholder="8" name="room_luminaries_y" id="room_luminaries_y" value="<?php echo isset($cookie_data['room_luminaries_y'])?$cookie_data['room_luminaries_y']:$room['luminaries_count_y'] ?>">
                            <label for="room_luminaries_y" id="room_luminaries_y-error" class="error"><?php echo form_error('room_luminaries_y') ?></label>
                            <label class="field-type">
                                <select class="select-filed-name">
                                    <option>Y</option>
                                </select>
                                <!-- <span class="customArrow"></span> -->
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                    <div class="form-group">
                        <label class="labelTxt">Total</label>
                        <div class="form-group-field">
                            <input disabled="true" value="<?php echo isset($cookie_data['room_luminaries_x'], $cookie_data['room_luminaries_y'])?(int)$cookie_data['room_luminaries_x'] * (int)$cookie_data['room_luminaries_y']:(int)$room['luminaries_count_x'] * (int)$room['luminaries_count_y'] ?>" readonly="true" type="text" name="xy_total" id="xy_total">
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
                            <input id="uploadfile" class="select-filed-name2 chooseFile" type="text" placeholder="Choose File" disabled="display" value="<?php echo isset($selected_product['product_name'])?$selected_product['product_name']:$roomProducts['title']; ?>">
                            <label for="product_id" id="product_id-error" class="error"><?php echo form_error('product_id') ?></label>
                            <label class="choosebtn">
                                <a name="choose_product" id="choose_product">Choose</a>
                            </label>
                        </div>
                    </div>
                </div>
                <?php if ((bool)$showSuspensionHeight) { ?>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label class="labelTxt">Pendant Length</label>
                        <div class="form-group-field form-dimention">
                            <input class="dialux-suggestions-fields number-only-field restrict-character" data-restrict-to="15" type="number" value="<?php echo $room['suspension_height'] ?>" name="pendant_length" id="pendant_length" placeholder="">
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
                <?php } ?>
            </div>
        </div>
        <div class="section-title clearfix clickable">
            <h3 class="pull-left">Advanced Options</h3>
            <div class="pull-right">
                <label class="toggle-switch">
                    <input type="checkbox" id="display-advanced-options">
                    <span class="toggle-slider"></span>
                </label>
            </div>
        </div>
        <hr>
        <div class="form-wrapper collapse" id="advanced-options-div" style="<?php echo !empty(array_intersect($validation_error_keys, ["rho_wall", "rho_ceiling", "rho_floor", "rho_floor", "lux_values"]))?"display:block":"" ?>">
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
                            <input class="dialux-suggestions-fields number-only-field restrict-character" data-restrict-to="15" type="text" name="rho_wall" value="<?php echo isset($cookie_data['rho_wall'])?$cookie_data['rho_wall']:$room['rho_wall'] ?>" placeholder="">
                            <label for="rho_wall" id="rho_wall-error" class="error"><?php echo form_error('rho_wall') ?></label>
                            <label class="field-type">
                                <select class="select-filed-name">
                                    <option class="text-center">%</option>
                                </select>
                                <!-- <span class="customArrow"></span> -->
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label class="labelTxt">Ceiling</label>
                        <div class="form-group-field">
                            <input class="dialux-suggestions-fields number-only-field restrict-character" data-restrict-to="15" type="text" name="rho_ceiling" value="<?php echo isset($cookie_data['rho_ceiling'])?$cookie_data['rho_ceiling']:$room['rho_ceiling'] ?>" placeholder="">
                            <label for="rho_ceiling" id="rho_ceiling-error" class="error"><?php echo form_error('rho_ceiling') ?></label>
                            <label class="field-type">
                                <select class="select-filed-name">
                                    <option class="text-center">%</option>
                                </select>
                                <!-- <span class="customArrow"></span> -->
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label class="labelTxt">Floor</label>
                        <div class="form-group-field">
                            <input class="dialux-suggestions-fields number-only-field restrict-character" data-restrict-to="15" type="text" name="rho_floor" value="<?php echo isset($cookie_data['rho_floor'])?$cookie_data['rho_floor']:$room['rho_floor'] ?>" placeholder="">
                            <label for="rho_floor" id="rho_floor-error" class="error"><?php echo form_error('rho_floor') ?></label>
                            <label class="field-type">
                                <select class="select-filed-name">
                                    <option class="text-center">%</option>
                                </select>
                                <!-- <span class="customArrow"></span> -->
                            </label>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label class="labelTxt">Maintainance Factor</label>
                        <div class="form-group-field">
                            <input class="dialux-suggestions-fields number-only-field restrict-character" data-restrict-to="15" type="text" name="maintainance_factor" value="<?php echo isset($cookie_data['maintainance_factor'])?$cookie_data['maintainance_factor']:$room['maintainance_factor'] ?>" placeholder="">
                            <label for="maintainance_factor" id="maintainance_factor-error" class="error"><?php echo form_error('maintainance_factor') ?></label>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label class="labelTxt">Lux Value</label>
                        <div class="form-group-field">
                            <input class="dialux-suggestions-fields number-only-field restrict-character" data-restrict-to="15" type="text" name="lux_values" value="<?php echo isset($cookie_data['lux_values'])?$cookie_data['lux_values']:$room['lux_value'] ?>" placeholder="">
                            <label for="lux_values" id="lux_values-error" class="error"><?php echo form_error('lux_values') ?></label>
                            <label class="field-type">
                                <select class="select-filed-name">
                                    <option class="text-center">LUX</option>
                                </select>
                                <!-- <span class="customArrow"></span> -->
                            </label>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
            
        <!-- //form wrapper -->
        <?php $disabled = ((isset($selected_product['product_name']) and '' != $selected_product['product_name'])||(isset($roomProducts['product_id']) && is_numeric($roomProducts['product_id']))) ? " " : " disabled "; ?>
        <!-- button wrapper section -->
        <div class="section-title clearfix">
            <div class="button-wrapper">
                <input <?php echo $disabled; ?> id="final-room-submission" type="submit" value="Done" class="custom-btn btn-margin btn-width save">
                <!-- <button type="button" class="custom-btn btn-margin btn-width cancel">Cancel</button> -->
            </div>
        </div>

        <input type="hidden" name="project_room_id" id="project_room_id" value="<?php echo $room['project_room_id'] ?>">
        <input type="hidden" name="enc_project_room_id" id="enc_project_room_id" value="<?php echo encryptDecrypt($room['project_room_id']) ?>">

        <input type="hidden" name="project_id" id="project_id" value="<?php echo $projectId; ?>">
        <input type="hidden" name="level" id="level" value="<?php echo $level; ?>">
        <input type="hidden" name="article_code" id="article_code" value="<?php echo isset($selected_product['articel_id'])?$selected_product['articel_id']:$roomProducts['articlecode']; ?>">
        <input type="hidden" name="type" id="type" value="<?php echo isset($selected_product['type'])?$selected_product['type']:$roomProducts['mounting_type']; ?>">
        <input type="hidden" name="product_id" id="product_id" value="<?php echo isset($selected_product['product_id'])?$selected_product['product_id']:$roomProducts['product_id']; ?>">
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