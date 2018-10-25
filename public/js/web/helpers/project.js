(function ($) {
    var normalizer = function (value) {
        return $.trim(value);
    };

    var validationRules = {
        // ignore: ":hidden:not(.selectpicker)",
        ignore: [],
        project_number: {
            normalizer: normalizer
        },
        project_name: {
            required: true,
            normalizer: normalizer
        },
        levels: {
            required: true,
            normalizer: normalizer
        },
        address: {
            required: true,
            normalizer: normalizer,
            minlength: 3,
            maxlength: 100
        }
    }

    $("#add_project").validate({
        rules: validationRules,
        submitHandler: function (form) {
            $(form).submit();
        }
    });

    $("#levels").on('change', function () {
        var self = this,
            $self = $(self),
            value = $self.val();
        if (value === "others") {
            $self.closest(".block-div")
                .removeClass("col-lg-4 col-md-4 col-sm-4 col-xs-12")
                .addClass("col-lg-2 col-md-2 col-sm-2 col-xs-6");
            $("#other-level-count-div").removeClass("concealable");
            
        } else {
            $self.closest(".block-div")
                .addClass("col-lg-4 col-md-4 col-sm-4 col-xs-12")
                .removeClass("col-lg-2 col-md-2 col-sm-2 col-xs-6");
            $("#other-level-count-div").addClass("concealable");
        }
    });

    $("#increment-others").on("click", function () {
        var self = this,
            $self = $(self),
            $otherProjectCount = $("#other-project-count");
        
        $otherProjectCount.val();
    });
})($);