<div class="inner-right-panel">

    <!--breadcrumb wrap-->
    <div class="breadcrumb-wrap">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo base_url("admin/templates") ?>">Template Management</a></li>
            <li class="breadcrumb-item active">Create Room Template</li>
        </ol>
    </div>
    <!--breadcrumb wrap close-->

    <!--Filter Section -->
    <div class="section">
        <div class="form-item-title clearfix">
            <!-- <h3 class="title">Create Room Template</h3> -->
        </div>
        <!-- title and form upper action end-->
         <?php echo form_open_multipart(base_url("admin/templates/add"), ['id' => 'create-room-template']); ?>
            <div class="row">
                <div class="col-lg-12 col-sm-12">
                    <label class="admin-label"><br></label>
                    <!-- thumb wrapper -->
                    <!-- cropper image preview box start-->
                    <div class="image-view-wrapper img-view200p img-viewbdr-radius4p">
                        <div class="image-view img-view200">
                            <div class="photo-upload-here">
                                <img style="width: 100%;height: 100%;" class="profile-pic" id="template_image" src="<?php echo (!empty($editdata['admin_profile_pic'])) ? $editdata['admin_profile_pic'] : DEFAULT_IMAGE ?>">
                            </div>

                            <div class="image_upload_trigger" onclick="addCoverImage()">
                                <a href="javascript:void(0);" class="upimage-btn">
                                </a>
                                <label class="camera" for="upload"><i class="fa fa-camera" aria-hidden="true"></i></label>
                                <input type="hidden" name="imgurl" class="inputhidden">
                                <input type="hidden" id="imgChange" name="imgChange" value="">
                            </div>
                        </div>
                    </div>
                    <!-- cropper image preview box end-->
                
                    <!-- //thumb wrapper -->
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label class="admin-label">Select Lighting</label>
                        <div class="display">
                            <select name="lighting" class="selectpicker platform">
                                <option value="">Select</option>
                                <option value="1" <?php echo set_select("lighting", "1") ?>>Residential</option>
                                <option value="2" <?php echo set_select("lighting", "2") ?>>Professional</option>
                            </select>
                            <span class=""></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label class="admin-label">Select Category</label>
                        <div class="display">
                            <select name="category" class="selectpicker platform">
                                <option value="">Select</option>
                                <?php foreach( $category_data as $data ) : ?>
                                <option value="<?php echo $data['id'] ?>" <?php echo set_select('category', $data['id']) ?>><?php echo $data['text'] ?></option>
                                <?php endforeach?>
                            </select>
                            <span class=""></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label class="admin-label">Select Room Type</label>
                        <div class="display">
                            <select name="room_type" class="selectpicker platform">
                                <option value="">Select</option>
                                <?php foreach( $room_data as $data ) : ?>
                                <option value="<?php echo $data['id'] ?>" <?php echo set_select('room_type', $data['id']) ?>><?php echo $data['text'] ?></option>
                                <?php endforeach?>
                            </select>
                            <span class=""></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label for="" class="admin-label">Room Length</label>
                        <div class="form-group-field form-dimention">
                        <input type="text" class="" value="<?php echo set_value('room_length') ?>" name="room_length" placeholder="0.00">
                            <label class="field-type">
                                <select class="select-filed-name" name="room_length_unit" placeholder="Unit">
                                    <option value="m" <?php echo set_select("room_length_unit", 'm') ?>>Meter</option>
                                    <option value="yd" <?php echo set_select("room_length_unit", 'yd') ?>>Yards</option>
                                    <option value="in" <?php echo set_select("room_length_unit", 'in') ?>>Inches</option>
                                </select>
                                <span class="customArrow"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label for="" class="admin-label">Room Breath</label>
                        <div class="form-group-field form-dimention">
                        <input type="text" class="" value="<?php echo set_value('room_breath') ?>" name="room_breath" value="" placeholder="0.00">
                            <label class="field-type">
                                <select class="select-filed-name" name="room_breath_unit" placeholder="Unit">
                                    <option value="m" <?php echo set_select("room_breath_unit", 'm') ?>>Meter</option>
                                    <option value="yd" <?php echo set_select("room_breath_unit", 'yd') ?>>Yards</option>
                                    <option value="in" <?php echo set_select("room_breath_unit", 'in') ?>>Inches</option>
                                </select>
                                <span class="customArrow"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label for="" class="admin-label">Room Height</label>
                        <div class="form-group-field form-dimention">
                            <input type="text" class="" value="<?php echo set_value('room_height') ?>" name="room_height" placeholder="0.00">
                            <label class="field-type">
                                <select class="select-filed-name" name="room_height_unit" placeholder="Unit">
                                    <option value="m" <?php echo set_select("room_height_unit", 'm') ?>>Meter</option>
                                    <option value="yd" <?php echo set_select("room_height_unit", 'yd') ?>>Yards</option>
                                    <option value="in" <?php echo set_select("room_height_unit", 'in') ?>>Inches</option>
                                </select>
                                <span class="customArrow"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label for="" class="admin-label">Workplane Height</label>
                        <div class="form-group-field form-dimention">
                            <input type="text" class="" value="<?php echo set_value('workplane_height') ?>" name="workplane_height" placeholder="0.00">
                            <label class="field-type">
                                <select class="select-filed-name" name="workplane_height_unit" placeholder="Unit">
                                    <option value="m" <?php echo set_select("workplane_height_unit", 'm') ?>>Meter</option>
                                    <option value="yd" <?php echo set_select("workplane_height_unit", 'yd') ?>>Yards</option>
                                    <option value="in" <?php echo set_select("workplane_height_unit", 'in') ?>>Inches</option>
                                </select>
                                <span class="customArrow"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label for="" class="admin-label">Room Shape</label>
                        <div class="display">
                            <select name="room_shape" class="selectpicker platform">
                                <option value="">Select</option>
                                <option value="1" <?php echo set_select("room_shape", "1") ?>>Rectangular</option>
                                <option value="2" <?php echo set_select("room_shape", "2") ?>>Circular</option>
                            </select>
                            <span class=""></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label for="" class="admin-label">Lux Value</label>
                        <div class="input-holder">
                            <input type="text" class="" value="<?php echo set_value('lux_value') ?>" name="lux_value" placeholder="0.00">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="button-wrap">
                    <input type="submit" class="commn-btn save" value="Add Room">
                </div>
            </div>

            <?php echo form_close(); ?>
            
    </div>
</div>
<link href="public/cropper/cropper.min.css" rel="stylesheet">
<script>
    if (location.hostname == "localhost") {
        var domain = location.protocol + '//' + location.hostname + (location.port ? ':' + location.port : '') + '/smartguide/admin';
        var domain2 = location.protocol + '//' + location.hostname + (location.port ? ':' + location.port : '') + '/smartguide';
    }
    else {
        var domain = location.protocol + '//' + location.hostname + (location.port ? ':' + location.port : '') + '/admin';
        var domain2 = location.protocol + '//' + location.hostname + (location.port ? ':' + location.port : '');
    }

     var optionsViewBuilder = function(data, defaultText) {
        
        var html = data.reduce(function(accumulator, currentValue){
            return accumulator + '<option value="' + currentValue.id + '">' + currentValue.text + "</option>";
        }, '<option value="">'+ defaultText +'</option>');

        return html;
    } 

    $("[name='lighting']").on("change", function(){
        var $self = $(this),
            value = $(this).val();

        if ( value.trim().length == 0 ) {
            return false;
        }
        
        $.ajax({
            url: domain2 + "/xhttp/application/" + value,
            dataType: "json",
            method: "GET",
            success: function(response) {
                if ( response.success ) {
                    $("[name='category']").html(optionsViewBuilder(response.data, "Select"));
                    $("[name='category']").selectpicker("refresh");
                } else {
                    // alert(response.message);
                }
            }
        });
    });

    $("[name='category']").on("change", function(){
        var $self = $(this),
            value = $(this).val();
        
        if ( value.trim().length == 0 ) {
            return false;
        }
        
        $.ajax({
            url: domain2 + "/xhttp/room/" + value,
            dataType: "json",
            method: "GET",
            success: function(response) {
                if ( response.success ) {
                    $("[name='room_type']").html(optionsViewBuilder(response.data, "Select"));
                    $("[name='room_type']").selectpicker('refresh');
                } else {
                    // alert(response.message); 
                }
            }
        });
    });

</script>
<script src="public/cropper/cropper.js"></script>
<script src="public/cropper/cropper.min.js"></script>
<script src="public/cropper/main.js"></script>
<script>
    function addCoverImage() {
        callme('coverPicInput','640','640','imagepicker2','addshopbtn','imageMe1','true','','circular');
    }
</script>