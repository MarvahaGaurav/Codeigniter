(function($){
    var $forgotPasswordButton = $("#forgot-password-btn");
    var $forgotPasswordField = $("#forgot-password-field");
    //
    var emailPattern = /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;
    if ( $forgotPasswordField.val().trim().match(emailPattern) ) {
        $forgotPasswordButton.prop("disabled", false);
    }
    $forgotPasswordField.on("change keyup", function(){
        var $self = $(this),
            isDisabled = true;
        if ( $self.val().trim().match(emailPattern) ) {
            isDisabled = false;
            var $errorField = $forgotPasswordField.siblings("label.error");
            if ( $errorField.length > 0 ) {
                $errorField.remove();
            }

        } else {
            isDisabled = true;
            var $errorField = $forgotPasswordField.siblings("label.error");
            if ( $errorField.length > 0 ) {
                $errorField.remove();
            }
            $forgotPasswordField.after("<label class='error'>Enter valid email</label>");
        }
        $forgotPasswordButton.prop("disabled", isDisabled);
    });

    $form = $("#forget-password-form");

    $form.on("submit", function(){
        var $self = $(this);

        $forgotPasswordButton.prop("disabled", true);
    });
})($);