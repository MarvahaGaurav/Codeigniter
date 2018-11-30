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
        location: 'lib/location'
    },
    shim: {
        //dependencies
        bootstrap: ['jquery'],
        selectPicker: ['bootstrap'],
        common: ['bootstrap'],
        jqueryScrollbar: ['jquery'],
        jqueryValidator: ['jquery'],
        autocomplete: ['jquery'],
        location: ['autocomplete']
    }
});

requirejs(
    ["jquery", "bootstrap", "common", "jqueryScrollbar", "jqueryValidator", "autocomplete", "location", "selectPicker"],
    function ($) {
        var normalizer = function (value) {
            return $.trim(value);
        };

        var $editForm = $("#edit_room_form"),
            $roomLuminariesX = $("#room_luminaries_x"),
            $roomLuminariesY = $("#room_luminaries_y"),
            $xyTotal = $("#xy_total"),
            $luxValues = $("input[name='lux_values']");

        $(".dialux-suggestions-fields").on("blur", function () {
            var self = this,
                $self = $(self),
                formData = getFormData($editForm);

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
                        $roomLuminariesX.attr("value", "");
                        $roomLuminariesX.attr("placeholder", "loading...");
                        $roomLuminariesX.attr("disabled", "disabled");
                        $roomLuminariesY.attr("value", "");
                        $roomLuminariesY.attr("placeholder", "loading...");
                        $roomLuminariesY.attr("disabled", "disabled");
                    },
                    success: function (response) {
                        $roomLuminariesX.attr("placeholder", "");
                        $roomLuminariesX.removeAttr("disabled");
                        $roomLuminariesY.attr("placeholder", "");
                        $roomLuminariesY.removeAttr("disabled");
                        if (response.success) {
                            $roomLuminariesX.val(response.data.luminaireCountInX);
                            $roomLuminariesY.val(response.data.luminaireCountInY);
                            $xyTotal.val(response.data.luminaireCount);
                            $luxValues.val((response.data.illuminance));
                        }
                    },
                    error: function(error) {
                        $roomLuminariesX.attr("placeholder", "");
                        $roomLuminariesX.removeAttr("disabled");
                        $roomLuminariesY.attr("placeholder", "");
                        $roomLuminariesY.removeAttr("disabled");
                    }
                });
            }

        });

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

        var $projectRoomId = $("#project_room_id");
        var projectRoomId = $projectRoomId.val();

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
            rho_wall: {
                number: true
            },
            rho_ceiling: {
                number: true
            },
            rho_floor: {
                number: true
            },
            rho_floor: {
                number: true
            },
            lux_values: {
                number: true
            }
        };

        $("#edit_room_form").validate({
            rules: validationRules
        });

        $("#advanced-option-div").on("click", function () {
            var self = this,
                $self = $(self);

            $self.toggleClass("dropup");
        });


        $(".is_number").keypress(function (e) {
            console.log(e.which);
            //if the letter is not digit then display error and don't type anything
            if (e.which != 46 && e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                return false;
            }
        });

        /**
         *
         */
        $("#choose_product").click(function () {
            let formData = $("#edit_room_form");
            let form_data = $(formData).serialize();
            console.log(form_data);
            /*Creating cookie with all form element*/
            eraseCookie("edit_room_form_data_" + projectRoomId);
            setCookie("edit_room_form_data_" + projectRoomId, form_data, 1);
            openNewWindow();
        });


        /**
         *
         * @param {type} name
         * @param {type} value
         * @param {type} days
         * @returns {undefined}
         */
        function setCookie(name, value, days) {
            var expires = "";
            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = "; expires=" + date.toUTCString();
            }
            document.cookie = name + "=" + (value || "") + expires + "; path=/";
        }


        /**
         *
         * @returns {undefined}
         */
        var openNewWindow = function () {
            let level = $("#level").val();
            let project_id = $("#project_id").val();
            let enc_project_room_id = $("#enc_project_room_id").val();
            let url = window.location.protocol + "//" + window.location.hostname + "/home/projects/" + project_id + "/levels/" + level + "/rooms/" + enc_project_room_id + "/edit/products";
            window.location = url;
        };


        /**
         *
         * @param {type} name
         * @returns {unresolved}
         */
        function getCookie(name) {
            var nameEQ = name + "=";
            var ca = document.cookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ')
                    c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) == 0)
                    return c.substring(nameEQ.length, c.length);
            }
            return null;
        }

        /**
         *
         * @param {type} name
         * @returns {undefined}
         */
        function eraseCookie(name) {
            document.cookie = name + '=; Max-Age=-99999999;';
        }



        /**
         *
         */
        $(":input").bind("keyup change", function (e) {
            let formData = $("#edit_room_form");
            let form_data = $(formData).serialize();
            /*Creating cookie with all form element*/
            eraseCookie("edit_room_form_data_" + projectRoomId);
            setCookie("edit_room_form_data_" + projectRoomId, form_data, 1);
        });


        /**
         *
         */
        $("#room_luminaries_x").keyup(function () {
            calculate();
        });


        /**
         *
         */
        $("#room_luminaries_y").keyup(function () {
            calculate();
        });


        /**
         *
         * @returns {undefined}
         */
        var calculate = function () {
            $("#xy_total_error").html("");
            let x = $("#room_luminaries_x").val();
            let y = $("#room_luminaries_y").val();
            let total = 0;
            if ('' != x && '' != y) {
                total = x * y;
                if (total > 500) {
                    total = 0;
                    $("#xy_total_error").html(" !>500");
                }
            }
            $("#xy_total").val(total);
        };


    }
);
