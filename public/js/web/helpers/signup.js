(function ($) {
    var $technicianDiv = $("#technician-div"),
        $ownerPrompt = $(".owner-prompt"),
        technicianTypes = ["2", "4", "5"],
        $technicianFields = $(".technician-fields"),
        $companyOwnerField = $('.company-owner-field'),
        $companyOwnerWrapper = $(".company-owner-wrapper"),
        $companyRegistrationNumber = $("#company-registration-number"),
        $companyName = $("#company-name"),
        $companyLogo = $("#company-logo"),
        $isCompanyOwner = $("input[name='is_company_owner']"),
        $companyNameWrapper = $("#company-name-wrapper"),
        $addressBox = $("#address-box-wrapper"),
        $address = $("#address")
    $contactNumber1 = $("#contact-number-1"),
        $contactNumber2 = $("#contact-number-2"),
        $userCountry = $("#user-country");

    var normalizer = function (value) {
        return $.trim(value);
    };

    $.validator.addMethod('filesize', function (value, element, param) {
        return this.optional(element) || (element.files[0].size <= param)
    }, 'File size must be less than {0}');

    var validationRules = {
        // ignore: ":hidden:not(.selectpicker)",
        image: {
            filesize: 2000
        },
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
            email: true,
            remote: {
                url: window.location.protocol + "//" + window.location.host + "/xhttp/check-email",
                method: "POST",
                data: {
                    email: function () {
                        return $("[name='email']").val();
                    }
                },
                dataType: "json",
                success: function (response) {
                    var validator = $("#signup-form").data("validator"),
                        element = $("[name='email']")[0],
                        valid = response.success,
                        errorMessage = response.msg;

                    remoteValidationHandler(validator, element, valid, errorMessage);
                }
            }
        },
        password: {
            required: true,
            minlength: 8
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
            number: true,
            remote: {
                url: window.location.protocol + "//" + window.location.host + "/xhttp/check-phone-number",
                method: "POST",
                data: {
                    country_code: function () {
                        return $("[name='contact_number_code']").val();
                    },
                    phone_number: function () {
                        return $("[name='contact_number']").val();
                    }
                },
                success: function (response) {
                    var validator = $("#signup-form").data("validator"),
                        element = $("[name='contact_number']")[0],
                        valid = response.success,
                        errorMessage = response.msg;

                    remoteValidationHandler(validator, element, valid, errorMessage);
                }
            }
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
            number: true,
            remote: {
                url: window.location.protocol + "//" + window.location.host + "/xhttp/check-alternate-phone-number",
                method: "POST",
                data: {
                    country_code: function () {
                        return $("[name='alternate_contact_number_code']").val();
                    },
                    phone_number: function () {
                        return $("[name='alternate_contact_number']").val();
                    }
                },
                success: function (response) {
                    var validator = $("#signup-form").data("validator"),
                        element = $("[name='alternate_contact_number']")[0],
                        valid = response.success,
                        errorMessage = response.msg;

                    remoteValidationHandler(validator, element, valid, errorMessage);
                }
            }
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
                $("#address-map-error").html(error);
            } else {
                $element.after(error);
            }
        },
        submitHandler: function (form) {
            $("#form-submit-button").attr("disabled", "disabled");
            form.submit();
        }
    });

    $contactNumber1.on('change', function () {
        var self = this,
            $self = $(self),
            value = $self.val(),
            countryCode = $self.find(':selected').attr('data-country');

        $("#contact-number-2 option").each(function (index, element) {
            $(element).removeAttr("selected");
        });
        $("#user-country option").each(function (index, element) {
            $(element).removeAttr("selected");
        });

        if ($("input[name='alternate_contact_number']").val().length == 0) {
            $("#contact-number-2 option[value='" + value + "']").attr('selected', "selected");
            $contactNumber2.selectpicker('destroy');
            $contactNumber2.selectpicker();
        }
        if ($("#select-city").val().length == 0) {
            $("#user-country option[value='" + countryCode + "']").attr('selected', 'selected');
            $userCountry.trigger('change');
            $userCountry.selectpicker('destroy');
            $userCountry.selectpicker();
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
            });
            $companyLogo.rules("add", {
                required: true,
            });
            $technicianFields.removeAttr("disabled");
        } else if (currentUserType == 6 || currentUserType == 3) {
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
                maxlength: 25
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
    });

    function remoteValidationHandler(validator, element, valid, errorMessage) {
        var previous = validator.previousValue(element);
        validator.settings.messages[element.name].remote = previous.originalMessage;
        if (valid) {
            submitted = validator.formSubmitted;
            validator.prepareElement(element);
            validator.formSubmitted = submitted;
            validator.successList.push(element);
            delete validator.invalid[element.name];
            validator.showErrors();
        } else {
            errors = {};
            message = errorMessage || validator.defaultMessage(element, "remote");
            errors[element.name] = previous.message = $.isFunction(message) ? message(value) : message;
            validator.invalid[element.name] = true;
            validator.showErrors(errors);
        }
        previous.valid = valid;
        validator.stopRequest(element, valid);
    }

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