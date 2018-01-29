/* elements */
               $password = $("#password");
                $confirmPassword = $("#confirm-password");
                $oldpassword=$("#oldpassword");
             
                errorMessage = [//corresponding error messages in sequence
                    "Password must contain atleast 6 characters.",
                    "Passwords don't match.",
                ];

        //On Submit handler
        $("#changepassword").on("submit", function () {
            /* password checks for email signup */
            if ($("#password").length === 1) {
                element = ["password"];
                pattern = [/^[\w\W]{6,}$/];

                try {
                    var error = validateFields(element, pattern);
                } catch (error) {
                    console.log(error.message, error.name);
                    return false;
                }
                //alert success should the fields be successfully validated
                for (index in error) {
                    if (error[index] === "true") {
                        $status = $("#" + element[index]).closest(".input-wrapper").siblings(".error").html("success");
                        $status.css({
                            color: "green"
                        });
                    } else {

                    }
                }

                //return false on unsuccessful validation
                if (error.indexOf("false") >= 0) {
                    $status = $("#" + element[error.indexOf("false")]).closest(".input-wrapper").siblings(".error").html(errorMessage[0]);
                    $status.css({
                        color: "indianred"
                    });
                    $("#" + element[error.indexOf("false")]).trigger("focus");
                    return false;
                } else {

                }

                if ($confirmPassword.val() !== $password.val()) {
                    $status = $($confirmPassword).closest(".input-wrapper").siblings(".error").html(errorMessage[1]);
                    $status.css({
                        color: "indianred"
                    });
                    $($confirmPassword).trigger("focus");
                    return false;
                } else {
                    $status = $($confirmPassword).closest(".input-wrapper").siblings(".error").html("success");
                    $status.css({
                        color: "green"
                    });
                    $($confirmPassword).trigger("focus");
                }
            }


            return true;
        });
        /* confirm password on blur valdation */
        $confirmPassword.on("blur", function () {
            if (validatePassword($password.val())) {
                if ($confirmPassword.val() !== $password.val()) {
                    $status = $confirmPassword.closest(".input-wrapper").siblings(".error").html(errorMessage[1]);
                    $confirmPassword.css({
                        border: "2px solid indianred"
                    });
                    $status.css({
                        color: "indianred"
                    });
                } else {
                    $status = $confirmPassword.closest(".input-wrapper").siblings(".error").html("success");
                    $confirmPassword.css({
                        border: "1px solid #d7f1f7"
                    });
                    $status.css({
                        color: "green"
                    });
                }
            } else {

            }
        });
        /* password  on blur validation */
        $password.on("blur", function () {
            onBlurChecks($password,
                    validatePassword($password.val().trim()),
                    $password.closest(".input-wrapper").siblings(".error"),
                    errorMessage[0]);
        });
        
        
        /* email on blur validation  */
		$oldpassword.on("blur", function () {
			var oldpassword = $oldpassword.val().trim();
                        var userid=$("#userid").val().trim();
                   
				$current = $(this);
			if (validatePassword(oldpassword)) {
				userData = {
					oldpassword: btoa(oldpassword),
                                        userid:userid,
					csrf_token: $("#csrf_token").val()
				}
				$.ajax({
					method: "post",
					data: userData,
					dataType: "json",
					url: "req/check-edit-passmatch-exists",
					success: function (response) {
                                             console.log(response);
						$("#csrf_token").val(response.csrf_token);
						if (response.error) {
							
                                                        $status = $current.closest(".input-wrapper").siblings(".error").html(response.message);
							$current.css({
								border: "1px solid #d7f1f7"
							});
							$status.css({
								color: "green"
							});
						} else {
							$status = $current.closest(".input-wrapper").siblings(".error").html("Old password not matched");
							$current.css({
								border: "2px solid indianred"
							});
							$status.css({
								
                                                                color: "indianred"
							});
						}
					}
				});
			} else {
				$status = $oldpassword.closest(".input-wrapper").siblings(".error").html(errorMessage[0]);
				$oldpassword.css({
					border: "2px solid indianred"
				});
				$status.css({
					color: "indianred"
				});
			}
                        
                        $("input[type='password']").on("focus", function(){
			var $current = $(this);
			$current.css({
				border: "1px solid #d7f1f7"
			});
			$current.closest(".input-wrapper").siblings(".error").html("");
		});

		});


