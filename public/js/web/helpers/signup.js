(function ($) {
    var $technicianDiv = $("#technician-div"),
        technicianTypes = ["2", "3", "4", "5"],
        $technicianFields = $(".technician-fields"),
        $companyOwnerField = $('.company-owner-field'),
        $companyOwnerWrapper = $(".company-owner-wrapper"),
        $companyRegistrationNumber = $("#company-registration-number"),
        $companyName = $("#company-name"),
        $companyLogo = $("#company-logo"),
        $isCompanyOwner = $("input[name='is_company_owner']");
        $companyNameWrapper = $("#company-name-wrapper");

    var normalizer = function (value) {
        return $.trim(value);
    };

    var validationRules = {
        // ignore: ":hidden:not(.selectpicker)",
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

    $("#signup-form").validate({
        ignore: [],
        rules: validationRules
    });

    $("#select-user-types").on('change', function () {
        var self = this,
            $self = $(self),
            currentUserType = $self.val();

        if (technicianTypes.indexOf(currentUserType) != -1) {
            $technicianDiv.show();
            $companyName.rules("add", {
                required: true,
                maxlength: 50,
                normalizer: normalizer
            });
            $isCompanyOwner.rules("add", {
                required: true,
            });
            $companyRegistrationNumber.rules("add", {
                required: true,
                number: true
            });
            $companyLogo.rules("add", {
                required: true,
            });
            $technicianFields.removeAttr("disabled");
        } else {
            $technicianDiv.hide();
            $companyRegistrationNumber.rules('remove');
            $companyLogo.rules('remove');
            $companyName.rules("remove");
            $isCompanyOwner.rules("remove");
            $technicianFields.attr("disabled", "disabled");
        }
    });

    $isCompanyOwner.on('click', function () {
        var self = this,
            $self = $(self),
            currentValue = $self.val();

        if (currentValue == 1) {
            companyNameView('owner');
            $isCompanyOwner.rules("add", {
                required: true,
            });
            $companyRegistrationNumber.rules("add", {
                required: true,
                number: true
            });
            $companyOwnerField.removeAttr("disabled");
            $companyOwnerWrapper.show();
        } else if (currentValue == 0) {
            $companyRegistrationNumber.rules('remove');
            $companyLogo.rules('remove');
            companyNameView('employee');
            $companyOwnerField.attr("disabled", "disabled");
            $companyOwnerWrapper.hide();
        } else {

        }
    });

    $("input[name='company_logo']").on('change', function (event) {
        var files = event.target.files;

        $("#uploadfile").val(files[0].name);
    })

    function companyNameView(type) {
        if (type == 'owner') {
            $(".company-name-select").selectpicker('destroy');
            $companyNameWrapper.html('<input type="text" id="company-name" name="company_name" class="form-control technician-fields" placeholder="Company Name"/>');
        } else if(type == 'employee') {
            $.ajax({
                url: 'xhttp/companies',
                dataType: 'json',
                beforeSend: function () {

                },
                success: function (response) {
                    var html = '<select name="company_name" class="company-name-select" data-style="btn-default custom-select-style">'
                        + '<option value="">Select a company</option>';
                    for(company in response.data) {
                        var companyObject = response.data[company];
                        html += '<option data-thumbnail="' +
                                companyObject.company_image + '" value="' +
                                companyObject.company_id + '">' +
                                companyObject.company_name + '</option>';
                    } 
                    html += '</select>';
                    $companyNameWrapper.html(html);
                    $(".company-name-select").selectpicker({
                        liveSearch: true
                    });

                }
            });
        }
    }
})($);