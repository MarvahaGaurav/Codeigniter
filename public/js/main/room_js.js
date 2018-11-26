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
        helper: 'web/helpers/room_js'
    },
    shim: {
        //dependencies
        bootstrap: ['jquery'],
        selectPicker: ['bootstrap'],
        common: ['bootstrap'],
        jqueryScrollbar: ['jquery'],
        jqueryValidator: ['jquery'],
        helper: ['jqueryValidator'],
        autocomplete: ['jquery'],
        location: ['autocomplete']
    }
});

requirejs(
    ["jquery", "bootstrap", "common", "jqueryScrollbar", "jqueryValidator", "helper", "autocomplete", "location", "selectPicker"],
    function ($) {
        fetchLocation('/xhttp/cities');

        $("#select-user-types").selectpicker();
        $(".contact-number").selectpicker();
        $("select[name='country']").selectpicker();

        var $addForm = $("#add_room_form"),
            $roomLuminariesX = $("#room_luminaries_x"),
            $roomLuminariesY = $("#room_luminaries_y"),
            $xyTotal = $("#xy_total"),
            $luxValues = $("input[name='lux_values']");
        
        addFormData = getFormData($addForm);

        if (
            ("room_id" in addFormData && addFormData["room_id"].trim().length > 0) &&
            ("article_code" in addFormData && addFormData["article_code"].trim().length > 0) &&
            ("product_id" in addFormData && addFormData["product_id"].trim().length > 0) &&
            ("length" in addFormData && addFormData["length"].trim().length > 0) &&
            ("width" in addFormData && addFormData["width"].trim().length > 0) &&
            ("height" in addFormData && addFormData["height"].trim().length > 0) &&
            ("lux_values" in addFormData && addFormData["lux_values"].trim().length > 0) &&
            ("maintainance_factor" in addFormData && addFormData["maintainance_factor"].trim().length > 0)
        ) {
            $.ajax({
                url: window.location.protocol + "//" + window.location.host + "/xhttp/quick-calc/suggestions",
                method: "POST",
                data: addFormData,
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        $roomLuminariesX.val(response.data.luminaireCountInX);
                        $roomLuminariesY.val(response.data.luminaireCountInY);
                        $xyTotal.val(response.data.luminaireCount);
                        $luxValues.val((response.data.illuminance));
                    }
                }
            });
        }

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

        $(".dialux-suggestions-fields").on("blur", function () {
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
                            $luxValues.val((response.data.illuminance));
                        }
                    }
                });
            }

        });
    },
    function () {

    }
);
