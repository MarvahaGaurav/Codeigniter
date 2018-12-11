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
        helper: 'web/helpers/quickcalc-luxvalues',
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
        helper: ['jqueryValidator'],
        roomForm: ['jquery']
    }
});

requirejs(
    ["jquery", "bootstrap", "common", "jqueryScrollbar", "jqueryValidator", "autocomplete", "location", "selectPicker", "roomForm", "helper"],
    function ($) {
        var $addForm = $("#quick_cal_form"),
            $roomLuminariesX = $("#room_luminaries_x"),
            $roomLuminariesY = $("#room_luminaries_y"),
            $xyTotal = $("#xy_total"),
            $luxValues = $("input[name='lux_values']"),
            $chooseProduct = $("#choose_product"),
            $roomPlaneHeight = $("input[name='room_plane_height']"),
            $rhoWall = $("input[name='rho_wall']"),
            $rhoCeiling = $("input[name='rho_ceiling']"),
            $rhoFloor = $("input[name='rho_floor']"),
            $maintainanceFactor = $("input[name='maintainance_factor']"),
            $luxValues = $("input[name='lux_values']"),
            $roomName = $("#room-name"),
            $roomTitle = $("#room-title"),
            $roomId = $("#room_id"),
            $applicationId = $("#application_id"),
            $application = $("#application"),
            $finalRoomSubmission = $("#final-room-submission");

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

        if ($application && $application.val() && $application.val().trim().length > 0) {

        }

        $application.on("change", function () {
            var self = this,
                $self = $(self),
                value = $self.val();

            if (value.trim().length == 0) {
                var roomEmptySelect = $("#room").children("option")[0].outerHTML;
                console.log(roomEmptySelect);
                $room.html(roomEmptySelect);
                $chooseProduct.attr("disabled", "disabled");
                return 0;
            }

            var roomHtml = $("#room").children("option[value='']")[0].outerHTML;

            $.ajax({
                url: window.location.protocol + "//" + window.location.host + '/xhttp/applications/rooms',
                method: "GET",
                dataType: "json",
                data: {
                    application_id: value
                },
                beforeSend: function () {
                    $room.html(roomHtml);
                    $room.attr('disabled', 'disabled');
                },
                success: function (response) {
                    $room.removeAttr('disabled');
                    if (response.success) {
                        var html = '',
                            data = response.data;

                        html = data.reduce(function (previousValue, currentValue) {
                            var jsonData = JSON.stringify(currentValue);
                            var currentData = "<option value='" + currentValue.room_id + "' data-json='" + jsonData + "'>" + currentValue.title + "</option>";
                            return previousValue + currentData;
                        }, roomHtml);

                        $room.html(html);
                    } else {
                        $("#room option").each(function (index, element) {
                            if ($(element).val().trim().length > 0) $(element).remove();
                        });
                    }
                }
            });
        });

        $("#room").on("change", function () {
            var self = this,
                $self = $(self),
                value = $self.val();

            if (value.trim().length == 0) {
                $chooseProduct.attr("disabled", "disabled");
                return 0;
            }

            var $selectedOption = $self.children('option:selected'),
                roomData = JSON.parse($selectedOption.attr('data-json'));

            if (value.trim().length > 0) {
                $roomPlaneHeight.val(roomData.reference_height * 100);
                $rhoWall.val(roomData.reflection_values_wall);
                $rhoCeiling.val(roomData.reflection_values_ceiling);
                $rhoFloor.val(roomData.reflection_values_floor);
                $maintainanceFactor.val(roomData.maintainance_factor);
                $luxValues.val(roomData.lux_values);
                $chooseProduct.addClass('redirectable');
                $chooseProduct.attr('data-redirect-to', window.location.protocol + "//" + window.location.host + '/home/fast-calc/lux/applications/' + roomData.application_id + '/rooms/' + roomData.room_id + '/products');
                $roomName.val(roomData.title);
                $roomTitle.html(roomData.title + " : ");
                $roomId.val(roomData.room_id);
                $applicationId.val(roomData.application_id);
                $chooseProduct.removeAttr('disabled');
                getDialuxData(self);
                // $(".dialux-suggestions-fields").trigger('change');
            } else {
                $roomPlaneHeight.val('');
                $rhoWall.val('');
                $rhoCeiling.val('');
                $rhoFloor.val('');
                $maintainanceFactor.val('');
                $luxValues.val('');
                $roomName.val('');
                $roomTitle.html('');
                $roomId.val(roomData.room_id);
                $applicationId.val(roomData.application_id);
                $chooseProduct.removeClass('redirectable');
                $chooseProduct.removeAttr('data-redirect-to');
                $chooseProduct.attr('disabled', 'disabled');
            }

        });

        $(".dialux-suggestions-fields").on("change keydown", function () {
            getDialuxData(this);
        });

        function getDialuxData(element)
        {
            var self = element,
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
                    beforeSend: function () {
                        $roomLuminariesX.attr("placeholder", "loading...");
                        $roomLuminariesX.attr("disabled", "disabled");
                        $roomLuminariesX.attr("value", "");
                        $roomLuminariesY.attr("placeholder", "loading...");
                        $roomLuminariesY.attr("disabled", "disabled");
                        $roomLuminariesY.attr("value", "");
                        $finalRoomSubmission.attr("disabled", "disabled");
                    },
                    success: function (response) {
                        $roomLuminariesX.attr("placeholder", "");
                        $roomLuminariesX.removeAttr("disabled");
                        $roomLuminariesY.attr("placeholder", "");
                        $roomLuminariesY.removeAttr("disabled");
                        $finalRoomSubmission.removeAttr("disabled");
                        if (response.success) {
                            $roomLuminariesX.val(response.data.luminaireCountInX);
                            $roomLuminariesY.val(response.data.luminaireCountInY);
                            $xyTotal.val(response.data.luminaireCount);
                            // $luxValues.val((response.data.illuminance));
                        }
                    },
                    error: function(error) {
                        $roomLuminariesX.attr("placeholder", "");
                        $roomLuminariesX.removeAttr("disabled");
                        $roomLuminariesY.attr("placeholder", "");
                        $roomLuminariesY.removeAttr("disabled");
                        $finalRoomSubmission.removeAttr("disabled");
                    }
                });
            }
        }
    }
);
