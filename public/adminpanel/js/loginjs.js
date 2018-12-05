
/*$(function() {
 var txt = $(".removespace");
 var func = function(e) {
 txt.val(txt.val().replace(/\s/g, ''));
 }
 txt.keyup(func).blur(func);
 });*/

//-----------------------------------------------------------------------
/**
 *@Description Here is the methods starts for the form validations in admin using jquery validator. 
 * 
 */
$(document).ready(function () {

    $.each($.validator.methods, function (key, value) {
        $.validator.methods[key] = function () {
            if (arguments.length > 0) {
                arguments[0] = $.trim(arguments[0]);
            }

            return value.apply(this, arguments);
        };
    });

    $.validator.addMethod("noSpace", function (value, element) {
        return value == '' || value.trim().length != 0;
    }, "");


    /**
     * @name validate add app version form
     * @description This method is used to validate add app version form.
     * 
     */
    $("#login_admin_form").validate({
        errorClass: "alert-danger",
        rules: {
            email: {
                required: true,
                email: true,
                noSpace: true
            },
            password: {
                required: true,
                noSpace: true
            },
        },
        messages: {
            email: {
                required: string.loginemail,

            },
            password: {
                required: string.loginpassword,

            },
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

    /**
     * @name validate add app version form
     * @description This method is used to validate add app version form.
     * 
     */
    $("#forget_pwd_admin_form").validate({
        errorClass: "alert-danger",
        rules: {
            email: {
                required: true,
                email: true
            },
        },
        messages: {
            email: {
                required: string.loginemail,

            },
        },
        submitHandler: function (form) {
            form.submit();
        }
    });


});
