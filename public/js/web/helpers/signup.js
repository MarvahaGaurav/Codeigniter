(function ($) {
    var $technicianDiv = $("#technician-div"),
        $ownerPrompt = $(".owner-prompt"),
        technicianTypes = ["2", "3", "4", "5"],
        $technicianFields = $(".technician-fields"),
        $companyOwnerField = $('.company-owner-field'),
        $companyOwnerWrapper = $(".company-owner-wrapper"),
        $companyRegistrationNumber = $("#company-registration-number"),
        $companyName = $("#company-name"),
        $companyLogo = $("#company-logo"),
        $isCompanyOwner = $("input[name='is_company_owner']"),
        $companyNameWrapper = $("#company-name-wrapper"),
        $addressBox = $("#address-box-wrapper"),
        $address = $("#address");

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
        rules: validationRules,
        errorPlacement: function (error, $element) {
            if ($element.attr('name') == 'address_lat' || $element.attr('name') == 'address_lng' || $element.attr('name') === 'address') {
                $("#maps-modal").modal('show');
                $("#address-map-error").html(error);
            } else {
                $element.after(error);
            }
        }
    });

    $("#select-user-types").on('change', function () {
        var self = this,
            $self = $(self),
            currentUserType = $self.val();


        if (technicianTypes.indexOf(currentUserType) != -1) {
            if ($("#company_owner_no").prop("checked")) {
                companyNameView('employee');
            } else if ($("#company_owner_yes").prop("checked")) {
                companyNameView('owner');
            }
            $ownerPrompt.show();
            $technicianDiv.show();
            if (currentUserType == 2 && $("#company_owner_yes").prop("checked")) {
                $("#address-box-wrapper").show();
                $addressBox.removeClass('concealable');
                $address.rules('add', {
                    required: true
                });
                $("#address-lat").rules('add', {
                    required: true,
                    number: true
                });
                $("#address-lng").rules('add', {
                    required: true,
                    number: true
                });
            } else {
                $("#address-box-wrapper").hide();
                $address.rules('remove');
                $("#address-lat").rules('remove');
                $("#address-lng").rules('remove');
            }
            $("#company-name").rules("add", {
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
        } else if (currentUserType == 6) {
            companyNameView('owner');
            $ownerPrompt.hide();
            $technicianDiv.show();
            $companyOwnerWrapper.show();
            $companyRegistrationNumber.removeAttr("disabled");
            $address.rules('remove');
            $("#address-lat").rules('remove');
            $("#address-lng").rules('remove');
            $("#address-box-wrapper").hide();
            $("#company_owner_yes").prop("checked", true);
            $("#company-name").rules("add", {
                required: true,
                maxlength: 50,
                normalizer: normalizer
            });
            $companyRegistrationNumber.rules("add", {
                required: true,
                maxlength: 25,
                number: true
            });
            $companyLogo.rules("add", {
                required: true,
            });
        } else {
            $("#address-box-wrapper").hide();
            $technicianDiv.hide();
            $companyRegistrationNumber.rules('remove');
            $companyLogo.rules('remove');
            $address.rules('remove');
            $("#address-lat").rules('remove');
            $("#address-lng").rules('remove');
            $("#company-name").rules("remove");
            $isCompanyOwner.rules("remove");
            $technicianFields.attr("disabled", "disabled");
        }
    });

    $isCompanyOwner.on('click', function () {
        var self = this,
            $self = $(self),
            currentValue = $self.val();

        if (currentValue == 1 && $("#select-user-types").val() == 2) {
            companyNameView('owner');
            $addressBox.show();
            $isCompanyOwner.rules("add", {
                required: true,
            });
            $companyRegistrationNumber.rules("add", {
                required: true,
                number: true
            });
            $address.rules('add', {
                required: true,
                maxlength: 100
            });
            $("#address-lat").rules('add', {
                required: true,
                number: true
            });
            $("#address-lng").rules('add', {
                required: true,
                number: true
            });
            $companyOwnerField.removeAttr("disabled");
            $companyOwnerWrapper.show();

        } else if (currentValue == 1) {
            $addressBox.hide();
            companyNameView('owner');
            $isCompanyOwner.rules("add", {
                required: true,
            });
            $companyRegistrationNumber.rules("add", {
                required: true,
                number: true
            });
            $address.rules('remove');
            $("#address-lat").rules('remove');
            $("#address-lng").rules('remove');
            $companyOwnerField.removeAttr("disabled");
            $companyOwnerWrapper.show();
        } else if (currentValue == 0) {
            $addressBox.hide();
            $address.rules('remove');
            $("#address-lat").rules('remove');
            $("#address-lng").rules('remove');
            $companyLogo.rules('remove');
            companyNameView('employee');
            $companyRegistrationNumber.rules('remove');
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
            $companyNameWrapper.html('<input type="text" id="company-name" name="company_name" class="form-control technician-fields alphanumspaces-only-field restrict-characters" data-restrict-to="100" placeholder="Company Name"/>');
        } else if (type == 'employee') {
            var userType = $("#select-user-types").val(),
                queryData = {
                    user_type: userType
                };
            $.ajax({
                url: 'xhttp/companies',
                dataType: 'json',
                data: queryData,
                beforeSend: function () {

                },
                success: function (response) {
                    var html = '<select name="company_name" class="company-name-select" data-style="btn-default custom-select-style">'
                        + '<option value="">Select a company</option>';
                    for (company in response.data) {
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