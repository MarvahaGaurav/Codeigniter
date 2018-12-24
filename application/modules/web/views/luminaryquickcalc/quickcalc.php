<div class="inner-container">
    <div class="container">

        <!-- breadcrumb -->
        <ul class="breadcrumb">
            <li><a href="javascript:void(0)">Home</a></li>
            <li class="active">No of Luminaries</li>
        </ul>
        <!-- //breadcrumb -->

        <div class="page-heading">
            <h1 class="page-title"><span id='room-title'></span> Room Dimensions</h1>
            <p class="prj-description">We are continously designing, prototyping and testing new products to enable us to deliver products that are energy efficient and environmental friendly, in combination
                with a creation of the ambiance that you need, always keeping in mind that luminaires have a great impact on the environment, appearance and impression of the overall
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
                        <label class="labelTxt">Room Name</label>
                        <div class="form-grouep-field">
                            <input type="text" name="name" id="room-name" placeholder="Livingroom" value="<?php echo isset($cookieData['name'])?$cookieData['name']:'' ?>" readonly>
                            <label for="name" id="name-error" class="error"><?php echo form_error('name') ?></label>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label class="labelTxt">Applications</label>
                            <div class="form-group-field">
                                <div class="form-group-field">
                                <select class="select-filed-name2" name="application" id="application">
                                    <option value="">Select Application</option>
                                    <?php foreach($applications as $application) { ?>
                                    <option value="<?php echo encryptDecrypt($application['application_id']) ?>" <?php echo (int)$application_id === (int)$application['application_id']?'selected':'' ?>><?php echo $application['title'] ?></option>
                                    <?php } ?>
                                </select>
                                <span class="customArrow"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label class="labelTxt">Rooms</label>
                        
                            <div class="form-group-field">
                                <div class="form-group-field">
                                <select class="select-filed-name2" name="room" id="room">
                                    <option value="">Select Room</option>
                                    <?php foreach($rooms as $room) { ?>
                                    <option data-json='<?php echo json_encode($room) ?>' value="<?php echo $room['room_id'] ?>" <?php echo $room_id === $room['room_id']?'selected':'' ?>><?php echo $room['title'] ?></option>
                                    <?php } ?>
                                </select>
                                <span class="customArrow"></span>
                            </div>

                        </div>
                    </div>
                </div>


                <div class="clearfix"></div>

                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label class="labelTxt">Room Length</label>
                        <div class="form-group-field form-dimention">
                            <input class="dialux-suggestions-fields number-only-field restrict-characters" data-restrict-to="15" type="text" placeholder="10" name="length" value="<?php echo isset($cookieData['length'])?$cookieData['length']:'' ?>">
                            <label for="length" id="length-error" class="error">
                                <?php echo form_error('length') ?>
                            </label>

                            <label class="field-type">
                                <select class="select-filed-name dialux-suggestions-fields room-dimension-units" name="length_unit">
                                    <?php foreach ($units as $unit) : ?>
                                    <option value="<?php echo strtolower($unit) ?>" <?php echo isset($cookieData['length_unit']) && $cookieData['length_unit'] === strtolower($unit)?"selected":"" ?>><?php echo $unit ?></option>
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
                            <input class="dialux-suggestions-fields number-only-field restrict-characters" data-restrict-to="15" type="text" placeholder="8" name="width" value="<?php echo isset($cookieData['width'])?$cookieData['width']:"" ?>">
                            <label for="width" id="width-error" class="error">
                                    <?php echo form_error('width') ?>
                            </label>

                            <label class="field-type">
                                <select class="select-filed-name dialux-suggestions-fields room-dimension-units" name="width_unit">
                                    <?php foreach ($units as $unit) : ?>
                                    <option value="<?php echo strtolower($unit) ?>" <?php echo isset($cookieData['width_unit']) && $cookieData['width_unit'] === strtolower($unit)?"selected":"" ?>><?php echo $unit ?></option>
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
                            <input class="dialux-suggestions-fields number-only-field restrict-characters" data-restrict-to="15" type="text" placeholder="8" name="height" value="<?php echo isset($cookieData['height'])?$cookieData['height']:"" ?>">
                            <label for="height" id="height-error" class="error">
                                    <?php echo form_error('height') ?>
                            </label>

                            <label class="field-type">
                                <select class="select-filed-name dialux-suggestions-fields room-dimension-units" name="height_unit">
                                    <?php foreach ($units as $unit) : ?>
                                    <option value="<?php echo strtolower($unit) ?>" <?php echo isset($cookieData['height_unit']) && $cookieData['height_unit'] === strtolower($unit)?"selected":"" ?>><?php echo $unit ?></option>
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
                            <input class="dialux-suggestions-fields number-only-field restrict-characters" data-restrict-to="15" class="is_number"  type="text" placeholder=""  name="room_plane_height" id="room_plane_height" value="<?php echo isset($cookieData['room_plane_height'])?$cookieData['room_plane_height']:'75' ?>">
                            <label for="room_plane_height" id="room_plane_height-error" class="error"><?php echo form_error('room_plane_height') ?></label>
                            <label class="field-type">
                                <select class="select-filed-name"  name="room_plane_height_unit" id="room_plane_height_unit">
                                    <option value="cms">Cms</option>
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
                            <input class="is_number number-only-field restrict-characters" data-restrict-to="2"  type="text" placeholder="8" name="room_luminaries_x" id="room_luminaries_x" value="<?php echo isset($cookieData['room_luminaries_x'])?$cookieData['room_luminaries_x']:"" ?>">
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
                            <input class="is_number number-only-field restrict-characters" data-restrict-to="2" type="text" placeholder="8" name="room_luminaries_y" id="room_luminaries_y" value="<?php echo isset($cookieData['room_luminaries_y'])?$cookieData['room_luminaries_y']:"" ?>">
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
                            <input disabled="true" value="<?php echo isset($cookieData['room_luminaries_x'], $cookieData['room_luminaries_y'])?(int)$cookieData['room_luminaries_x'] * (int)$cookieData['room_luminaries_y']:"" ?>" readonly="true" type="text" name="xy_total" id="xy_total">
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
                            <input id="uploadfile" class="select-filed-name2 chooseFile" type="text" placeholder="Choose File" disabled="display" value="<?php echo isset($selectedProduct['product_name'])?$selectedProduct['product_name']:''; ?>">
                            <label for="product_id" id="product_id-error" class="error"><?php echo form_error('product_id') ?></label>
                            <label class="choosebtn">
                                <button <?php echo isset($selectedProduct['product_id'], $selectedProduct['articel_id'], $selectedProduct['product_name'])?'':'disabled' ?> data-redirect-to="<?php echo isset($cookieData['application'], $cookieData['room'])?base_url("/home/fast-calc/luminary/applications/" . $cookieData['application'] . '/rooms/' . $cookieData['room'] . '/products'):'javascript:void(0)' ?>" class="redirectable" type="button" name="choose_product" id="choose_product">Choose</button>
                            </label>
                        </div>
                    </div>
                </div>


                
                <?php if ((bool)$showSuspensionHeight) { ?>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label class="labelTxt">Pendant Length</label>
                        <div class="form-group-field form-dimention">
                            <input class="dialux-suggestions-fields number-only-field restrict-characters" data-restrict-to="15" type="text" name="pendant_length" id="pendant_length" placeholder="" value="0.00">
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
                            <input class="dialux-suggestions-fields number-only-field restrict-characters" data-restrict-to="15" type="text" name="rho_wall" value="<?php echo isset($cookieData['rho_wall'])?$cookieData['rho_wall']:'0.8' ?>" placeholder="">
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
                            <input class="dialux-suggestions-fields number-only-field restrict-characters" data-restrict-to="15" type="text" name="rho_ceiling" value="<?php echo isset($cookieData['rho_ceiling'])?$cookieData['rho_ceiling']:'0.8' ?>" placeholder="">
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
                            <input class="dialux-suggestions-fields number-only-field restrict-characters" data-restrict-to="15" type="text" name="rho_floor" value="<?php echo isset($cookieData['rho_floor'])?$cookieData['rho_floor']:'0.3' ?>" placeholder="">
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
                            <input class="dialux-suggestions-fields number-only-field restrict-characters" data-restrict-to="15" type="text" name="maintainance_factor" value="<?php echo isset($cookieData['maintainance_factor'])?$cookieData['maintainance_factor']:'0.85' ?>" placeholder="">
                            <label for="maintainance_factor" id="maintainance_factor-error" class="error"><?php echo form_error('maintainance_factor') ?></label>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label class="labelTxt">Lux Value</label>
                        <div class="form-group-field">
                            <input class="dialux-suggestions-fields number-only-field restrict-characters" data-restrict-to="15" type="text" name="lux_values" value="<?php echo isset($cookieData['lux_values'])?$cookieData['lux_values']:'' ?>" placeholder="">
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

        <!-- button wrapper section -->
        <div class="section-title clearfix">
            <div class="button-wrapper">
                <input name="evaluate_btn" id="final-room-submission" type="submit" value="Calculate" class="custom-btn btn-margin btn-width save">
            </div>
        </div>
        <!-- button wrapper section -->
        <input type="hidden" name="room_id" id="room_id" value="<?php echo isset($cookieData['room_id'])?$cookieData['room_id']:'' ?>">
        <input type="hidden" name="application_id" id="application_id" value="<?php echo isset($cookieData['application_id'])?$cookieData['application_id']:'' ?>">
        <input type="hidden" name="article_code" id="article_code" value="<?php echo isset($selectedProduct['articel_id'])?$selectedProduct['articel_id']:'' ?>">
        <input type="hidden" name="type" id="type" value="<?php echo isset($selectedProduct['type'])?$selectedProduct['type']:'' ?>">
        <input type="hidden" name="product_id" id="product_id" value="<?php echo isset($selectedProduct['product_id'])?$selectedProduct['product_id']:'' ?>">
        <?php echo form_close(); ?>
        <!-- no record found -->
        <!-- <div class="no-record text-center">
            <img src="../images/no-found-note.png" alt="Note Paper">
            <p>You have no project.</p>
            <p>You have to <a href="login.html" class="login">Login</a> first to add a project!</p>
        </div> -->
        <!-- no record found -->

    </div>
</div>