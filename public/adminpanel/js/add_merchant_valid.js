   /*Image upload ajax*/
//profile picture
var domain =window.location.origin;
		$("#upload").on("change", function () {
			var formData = new FormData(),
                      
				$current = $(this),
                                
				current = this;
			formData.append("csrf_token", $("#csrf_token").val());
			formData.append("image", $current[0].files[0]);
                        
			$.ajax({
				url: "req/upload/profile-picture",
				method: "post",
				data: formData,
				dataType: "json",
				cache: false,
				contentType: false,
				processData: false,
                            beforeSend: function(){
                                 $(".loder-wrraper-single").show();
                               },
                               complete: function(){
                                 $(".loder-wrraper-single").hide();
                               },
				success: function (response) {
					console.log(response);
					if (response.success) {
						$("#profile-picture").val(response.data);
//						$(".profilePic").attr("src", response.data);
						$('#profilePic').css('background-image', 'url(' + response.data + ')');
                                        // previewImage(this, $(".profilePic"));
					}

					$("#csrf_token").val(response.csrf_token);
				}
			})

		});                  
                  /*Validation for Add merchant*/  
                  var $Merchant_Name = $("#Merchant_Name"),
			$email = $("#email"),
			$password = $("#password"),
			$confirmPassword = $("#confirm-password"),
			$mobileNumber = $("#mobile-number"),
			$address = $("#address"),
			$description = $("#description"),
			errorMessage = [ //corresponding error messages in sequence
				"Merchant Name is required and must contain only letters.",
				"Enter valid email.",
                                "Enter valid 10 digit mobile number",
                                "Password must contain atleast 6 characters.",
                                "Passwords don't match.",
				"Address Can not be empty.",
				"Description Can not be empty"
			];
                        //On Submit handler
		$("#Addmarchet").on("submit", function () {
			//elements to validate without the '#'
			var element = [
				"Merchant_Name", "email", "mobile-number"
			]; 

			//patterns to validate with must correspond to the elements array
			var pattern = [
				"^[a-zA-Z\\s]+$",
				/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
				"^\\d{10}$"
			];

			try {
				var error = validateFields(element, pattern);
			} catch (error) {
				console.log(error.message, error.name);
				return false; 
			}
			//alert success should the fields be successfully validated
			for (index in error) {
				if (error[index] === "true") {
					$status = $("#" + element[index]).closest(".input-holder").siblings(".error").html("success");
					$status.css({
						color: "green"
					});
				}
			}
 
			//return false on unsuccessful validation
			if (error.indexOf("false") >= 0) {
				$status = $("#" + element[error.indexOf("false")]).closest(".input-holder").siblings(".error").html(errorMessage[error.indexOf("false")] + "");
				$status.css({
					color: "indianred"
				});
				$("#" + element[error.indexOf("false")]).trigger("focus");
				return false;
			} else {

			}

			//check for user type field
//			if (typeof $(".user-type:checked").val() !== "undefined" && $(".user-type:checked").val() == 1) {
				/* age validation checks */
//				var dob = $dateOfBirth.val();
//				dob = dob.toString().split("/");
//
//				var age = getAge(dob[2] + "-" + dob[1] + "-" + dob[0]);
//
//				if ($dateOfBirth.val().trim().length === 0) {
//					$status = $dateOfBirth.closest(".input-holder").siblings(".error").html("Field can't be empty");
//					$status.css({
//						color: "indianred"
//					});
//					$dateOfBirth.trigger("focus");
//					return false;
//				} else if (age < 18) {
//					$status = $dateOfBirth.closest(".input-holder").siblings(".error").html(errorMessage[7]);
//					$status.css({
//						color: "indianred"
//					});
//					$dateOfBirth.trigger("focus");
//					return false;
//				} else {
//					$status = $dateOfBirth.closest(".input-holder").siblings(".error").html("success");
//					$status.css({
//						color: "green"
//					});
//				}

			
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
						$status = $("#" + element[index]).closest(".input-holder").siblings(".error").html("success");
						$status.css({
							color: "green"
						});
					} else {

					}
				}

				//return false on unsuccessful validation
				if (error.indexOf("false") >= 0) {
					$status = $("#" + element[error.indexOf("false")]).closest(".input-holder").siblings(".error").html(errorMessage[3]);
					$status.css({
						color: "indianred"
					});
					$("#" + element[error.indexOf("false")]).trigger("focus");
					return false;
				} else {

				}

				if ($confirmPassword.val() !== $password.val()) {
					$status = $($confirmPassword).closest(".input-holder").siblings(".error").html(errorMessage[4]);
					$status.css({
						color: "indianred"
					});
					$($confirmPassword).trigger("focus");
					return false;
				} else {
					$status = $($confirmPassword).closest(".input-holder").siblings(".error").html("success");
					$status.css({
						color: "green"
					});
					$($confirmPassword).trigger("focus");
				}
			}
                        
                        if($address.val()==''){
                               $('#addresserror').html('Please fill Address of merchant');
                               return false;
                        }
                        	return true;
		}); /* on submit */

		/* First name validation */
		$Merchant_Name.on("blur", function () {
			onBlurChecks($Merchant_Name,
				validateName($Merchant_Name.val().trim()),
				$Merchant_Name.closest(".input-holder").siblings(".error"),
				errorMessage[0]);
		});

		/* email on blur validation  */
		$email.on("blur", function () {
			var email = $email.val().trim(),
				$current = $(this);
			if (validateEmail(email)) {
				userData = {
					email: email,
					csrf_token: $("#csrf_token").val()
				}
				$.ajax({
					method: "post",
					data: userData,
					dataType: "json",
					url: "req/check-email-exists",
					success: function (response) {
						$("#csrf_token").val(response.csrf_token);
						if (!response.error) {
							$status = $current.closest(".input-holder").siblings(".error").html("success");
							$current.css({
								border: "1px solid #d7f1f7"
							});
							$status.css({
								color: "green"
							});
						} else {
							$status = $current.closest(".input-holder").siblings(".error").html(response.message);
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
				$status = $email.closest(".input-holder").siblings(".error").html(errorMessage[1]);
				$email.css({
					border: "2px solid indianred"
				});
				$status.css({
					color: "indianred"
				});
			}

		});

		/* mobile number on blur validation */
		$mobileNumber.on("blur", function () {
			var mobile = $mobileNumber.val().trim(),
				$current = $(this);
			if (validateMobileNumber(mobile)) {
				userData = {
					mobile_number: mobile,
                                        csrf_token: $("#csrf_token").val()
				}
				$.ajax({
					method: "post",
					data: userData,
					dataType: "json",
					url: "req/check-mobile-exists",
					success: function (response) {
						if (!response.error) {
							$status = $current.closest(".input-holder").siblings(".error").html("success");
							$current.css({
								border: "1px solid #d7f1f7"
							});
							$status.css({
								color: "green"
							});
						} else {
							$status = $current.closest(".input-holder").siblings(".error").html(response.message);
							$current.css({
								border: "2px solid indianred"
							});
							$status.css({
								color: "indianred"
							});
						}
                                                $("#csrf_token").val(response.csrf_token);
					}
				});
			} else {
				$status = $mobileNumber.closest(".input-holder").siblings(".error").html(errorMessage[2]);
				$mobileNumber.css({
					border: "2px solid indianred"
				});
				$status.css({
					color: "indianred"
				});
			}
		});
//		/* date of birth validator */
//		$dateOfBirth.on("blur", function () {
//			//check for user type field
//			//if (typeof $(".user-type:checked").val() !== "undefined" && $(".user-type:checked").val() == 1) {
//				var dob = $dateOfBirth.val();
//				dob = dob.toString().split("/");
//				var age = getAge(dob[2] + "-" + dob[1] + "-" + dob[0]);
//				if (dob.length === 1) {
//					$status = $dateOfBirth.closest(".input-holder").siblings(".error").html("Field can't be empty");
//					$dateOfBirth.css({
//						border: "2px solid indianred"
//					});
//					$status.css({
//						color: "indianred"
//					});
//				} else if (age < 18) {
//					$status = $dateOfBirth.closest(".input-holder").siblings(".error").html(errorMessage[7]);
//					$dateOfBirth.css({
//						border: "2px solid indianred"
//					});
//					$status.css({
//						color: "indianred"
//					});
//				} else {
//					$status = $dateOfBirth.closest(".input-holder").siblings(".error").html("success");
//					$dateOfBirth.css({
//						border: "1px solid #d7f1f7"
//					});
//					$status.css({
//						color: "green"
//					});
//				}
////			}else if ( $(".user-type:checked").val() == 2 ) {
////				$dateOfBirth.val("");
////				$status = $dateOfBirth.closest(".input-holder").siblings(".error").html("");
////				$dateOfBirth.css({
////					border: "1px solid #d7f1f7"
////				});
////			} else {
////
////			}
//		});
		/* confirm password on blur valdation */
		$confirmPassword.on("blur", function () {
			if (validatePassword($password.val())) {
				if ($confirmPassword.val() !== $password.val()) {
					$status = $confirmPassword.closest(".input-holder").siblings(".error").html(errorMessage[4]);
					$confirmPassword.css({
						border: "2px solid indianred"
					});
					$status.css({
						color: "indianred"
					});
				} else {
					$status = $confirmPassword.closest(".input-holder").siblings(".error").html("success");
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
				$password.closest(".input-holder").siblings(".error"),
				errorMessage[3]);
		});


