requirejs.config({
    baseUrl: "public/js",
    waitSeconds: 60,
    paths: {
        jquery: "jquery.min",
        bootstrap: "bootstrap.min",
        common: "web/common",
        selectPicker: "bootstrap-select.min",
        jqueryScrollbar: "plugin/jquery.scrollbar.min",
        jqueryValidator: 'jquery.validate.min',
        autocomplete: 'jquery.autocomplete.min',
        location: 'lib/location',
        roomForm: 'web/helpers/room-form'
    },
    shim: {
        //dependencies
        bootstrap: ['jquery'],
        selectPicker: ['bootstrap'],
        common: ['bootstrap'],
        jqueryScrollbar: ['jquery'],
        jqueryValidator: ['jquery'],
        autocomplete: ['jquery'],
        location: ['autocomplete'],
        roomForm: ['jquery']
    }
});

requirejs(
    ["jquery", "bootstrap", "common", "jqueryScrollbar", "jqueryValidator", "autocomplete", "location", "selectPicker", "roomForm"],
    function ($) {
        var $addForm = $("#quick_cal_form"),
            $roomLuminariesX = $("#room_luminaries_x"),
            $roomLuminariesY = $("#room_luminaries_y"),
            $xyTotal = $("#xy_total"),
            $luxValues = $("input[name='lux_values']");

        var $advancedOptionsDiv = $("#advanced-options-div");
        $("#display-advanced-options").on("change", function () {
            var self = this,
                $self = $(self);

            if (self.checked) {
                $advancedOptionsDiv.slideDown();
            } else {
                $advancedOptionsDiv.slideUp();
            }
        });

        var $room = $("#room");

        $("#application").on("change", function () {
            var self = this,
                $self = $(self),
                value = $self.val();

            $.ajax({
                url:  window.location.protocol + "//" + window.location.host + '/xhttp/applications/rooms',
                method: "GET",
                dataType: "json",
                data: {
                    application_id: value
                },
                success: function (response) {
                    var html = '',
                        data = response.data;

                    var roomHtml = $room.html();
                    
                    html = data.reduce(function(previousValue, currentValue){
                        var jsonData = JSON.stringify(currentValue);
                        var currentData = "<option value='"+ currentValue.room_id + "' data-json='"+ jsonData +"'>"+ currentValue.title +"</option>";
                        return previousValue + currentData;
                    }, roomHtml);

                    $room.html(html);
                }
            });
        });

        $("#room").on("change", function () {
            var self = this,
                $self = $(self),
                value = $self.val();

            var $selectedOption = $self.children('option:selected'),
                roomData = JSON.parse($selectedOption.attr('data-json'));

            $("input[name='room_plane_height']").val(roomData.reference_height * 100);
            $("input[name='rho_wall']").val(roomData.reflection_values_wall);
            $("input[name='rho_ceiling']").val(roomData.reflection_values_ceiling);
            $("input[name='rho_floor']").val(roomData.reflection_values_floor);
            $("input[name='maintainance_factor']").val(roomData.maintainance_factor);
            $("input[name='lux_values']").val(roomData.lux_values);
            
        });

        $(".dialux-suggestions-fields").on("change keydown", function () {
            var self = this,
                $self = $(self),
                formData = getFormData($addForm);

            if (
                ("room_id" in formData && formData["room_id"].trim().length > 0) &&
                ("article_code" in formData && formData["article_code"].trim().length > 0) &&
                ("product_id" in formData && formData["product_id"].trim().length > 0) &&
                ("length" in formData && formData["length"].trim().length > 0) &&
                ("width" in formData && formData["width"].trim().length > 0) &&
                ("height" in formData && formData["height"].trim().length > 0) &&
                ("lux_values" in formData && formData["lux_values"].trim().length > 0) &&
                ("maintainance_factor" in formData && formData["maintainance_factor"].trim().length > 0)
            ) {
                $.ajax({
                    url: window.location.protocol + "//" + window.location.host + "/xhttp/quick-calc/suggestions",
                    method: "POST",
                    data: formData,
                    dataType: "json",
                    success: function (response) {
                        if (response.success) {
                            $roomLuminariesX.val(response.data.luminaireCountInX);
                            $roomLuminariesY.val(response.data.luminaireCountInY);
                            $xyTotal.val(response.data.luminaireCount);
                            // $luxValues.val((response.data.illuminance));
                        }
                    }
                });
            }

        });

        var calculate = function () {
            $( "#xy_total_error" ).html( "" );
            let x = $( "#room_luminaries_x" ).val();
            let y = $( "#room_luminaries_y" ).val();
            let total = 0;
            if ( '' != x && '' != y ) {
                total = x * y;
                if ( total > 500 ) {
                    total = 0;
                    $( "#xy_total_error" ).html( " !>500" );
                }
            }
            $( "#xy_total" ).val( total );
        };

        var normalizer = function ( value ) {
            return $.trim( value );
        };

        var validationRules = {
            // ignore: ":hidden:not(.selectpicker)",
            room_refrence: {
                required: true,
                normalizer: normalizer
            },
            length: {
                required: true,
                normalizer: normalizer,
                number: true
            },
            width: {
                required: true,
                normalizer: normalizer,
                number: true
            },
            height: {
                required: true,
                normalizer: normalizer,
                number: true
            },
            room_plane_height: {
                required: true,
                normalizer: normalizer,
                number: true
            },
            room_luminaries_x: {
                required: true,
                normalizer: normalizer,
                number: true
            },
            room_luminaries_y: {
                required: true,
                normalizer: normalizer,
                number: true
            }, 
            maintainance_factor: {
                required: true,
                normalizer: normalizer,
                number: true
            },
            rho_wall:{
                number: true  
            },
            rho_ceiling:{
                number: true  
            },
            rho_floor:{
                number: true  
            },
            rho_floor:{
                number: true  
            },
            lux_values:{
                required: true,
                number: true  
            }
        };

        $( "#quick_cal_form" ).validate( {
            onclick: false, // <-- add this option
            rules: validationRules,

            messages: {
                length: {
                    required: "This field is required."
                }
            },

            errorPlacement: function (error, element) {
                alert('nknnn');
            }
            /*errorPlacement: function (error, $element) {
                alert('hi');
                $("#advanced-options-div").slideDown();
                if ($element.attr('name') == 'maintainance_factor' || $element.attr('name') == 'lux_values' ) {
                    $("#advanced-options-div").slideDown();
                } else {
                    $element.after(error);
                }
            },*/
        } );


        $( ".is_number" ).keypress( function ( e ) {
            //if the letter is not digit then display error and don't type anything
            if ( e.which != 46 && e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57) ) {
                return false;
            }
        } );


    }
);
