(function ($) {
    var $isInstallerOwner = $("#is-installer-owner");
    var normalizer = function (value) {
        return $.trim(value);
    };

    var validationRules = {
        // ignore: ":hidden:not(.selectpicker)",
        project_number: {
        },
        project_name: {
            required: true,
        },
        levels: {
            required: true,
        },
        address: {
            required: true,
            minlength: 3,
            maxlength: 500
        },
        address_lat: {
            required: true,
            number: true
        },
        address_lng: {
            required: true,
            number: true
        }
    }

    if ($isInstallerOwner.attr("data-status") == "true") {
        validationRules['installers'] = {
            required: true
        };
    }

    $("#add_project").validate({
        ignore: [],
        rules: validationRules,
        errorPlacement: function (error, $element) {
            if ($element.attr('id') == 'other-project-count') {
                $("#other-levels-wrapper").after(error);
            } else if ($element.attr('name') == 'project_name' || $element.attr('name') == 'project_number') {
                $element.after(error);
            } else if ($element.attr('name') == 'address_lat' || $element.attr('name') == 'address_lng' || $element.attr('name') === 'address') {
                $("#address-map-error").html(error);
            }
        },
        submitHandler: function (form) {
            $("#form-submit-button").attr("disabled", "disabled");
            form.submit();
        }
    });

    var $otherLevelCountDiv = $("#other-level-count-div"),
        $otherProjectCount = $("#other-project-count");

    $("#levels").on('change', function () {
        var self = this,
            $self = $(self),
            value = $self.val();
        if (value === "others") {
            $self.closest(".block-div")
                .removeClass("col-lg-4 col-md-4 col-sm-4 col-xs-12")
                .addClass("col-lg-2 col-md-2 col-sm-2 col-xs-6");
            $otherLevelCountDiv.removeClass("concealable");
            $self.attr('name', '');
            $otherProjectCount.attr('name', 'levels');

        } else {
            $self.closest(".block-div")
                .addClass("col-lg-4 col-md-4 col-sm-4 col-xs-12")
                .removeClass("col-lg-2 col-md-2 col-sm-2 col-xs-6");
            $otherLevelCountDiv.addClass("concealable");
            $self.attr('name', 'levels');
            $otherProjectCount.attr('name', '');
        }
    });

})($);