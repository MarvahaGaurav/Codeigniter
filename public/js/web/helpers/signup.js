(function ($) {
    var $technicianDiv = $("#technician-div"),
        technicianTypes = ["2", "3", "4", "5"],
        $technicianFields = $(".technician-fields"),
        $companyOwnerFields = $(".company-owner-fields"),
        $companyRegistrationNumber = $("#company-registration-number"),
        $companyName = $("#company-name"),
        $companyLogo = $("#company-logo"),
        $isCompanyOwner = $("input[name='is_company_owner']");

    var normalizer = function (value) {
        return $.trim(value);
    };

    var validationRules = {
        ignore: ":hidden:not(.selectpicker)",
        ignore: [],
        user_type: {
            required: true,
            normalizer: normalizer
        },
        fullname: {
            required: true,
            normalizer: normalizer
        },
        email: {
            required: true,
            normalizer: normalizer,
            email: true
        },
        password: {
            required: true,
            minlength: 6
        },
        confirm_password: {
            equalTo: "#password"
        },
        contact_number_code: {
            required: true,
            normalizer: normalizer,
        },
        contact_number: {
            required: true,
            normalizer: normalizer,
            minlength: 6,
            maxlength: 20,
            number: true
        },
        alternate_contact_number_code: {
            required: true,
            normalizer: normalizer
        },
        alternate_contact_number: {
            required: true,
            normalizer: normalizer,
            minlength: 6,
            maxlength: 20,
            number: true
        },
        country: {
            required: true,
            normalizer: normalizer
        },
        city: {
            required: true,
            normalizer: normalizer
        },
        zipcode: {
            required: true,
            normalizer: normalizer,
            number: true,
            minlength: 3,
            maxlength: 10
        },
    }

    // $("#signup-form").validate({
    //     rules: validationRules
    // });

    $("#select-user-types").on('change', function () {
        var self = this,
            $self = $(self),
            currentUserType = $self.val();

        if (technicianTypes.indexOf(currentUserType) != -1) {
            $technicianDiv.show();
            // $companyName.rules("add", {
            //     required: true,
            //     maxlength: 50,
            //     normalizer: normalizer
            // });
            // $isCompanyOwner.rules("add", {
            //     required: true,
            // });
            // $companyRegistrationNumber.rules("add", {
            //     required: true,
            //     number: true
            // });
            // $companyLogo.rules("add", {
            //     required: true,
            // });
            $technicianFields.removeAttr("disabled");
        } else {
            $technicianDiv.hide();
            // $companyRegistrationNumber.rules('remove');
            // $companyLogo.rules('remove');
            // $companyName.rules("remove");
            // $isCompanyOwner.rules("remove");
            $technicianFields.attr("disabled", "disabled");
        }
    });

    $isCompanyOwner.on('click', function () {
        var self = this,
            $self = $(self),
            currentValue = $self.val();

        if (currentValue == 1) {
            // $isCompanyOwner.rules("add", {
            //     required: true,
            // });
            // $companyRegistrationNumber.rules("add", {
            //     required: true,
            //     number: true
            // });
            $companyOwnerFields.show();
        } else if (currentValue == 0) {
            // $companyRegistrationNumber.rules('remove');
            // $companyLogo.rules('remove');
            $companyOwnerFields.hide();
        } else {

        }
    })
})($);