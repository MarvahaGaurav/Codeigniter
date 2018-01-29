requirejs.config({
	baseUrl: 'public/adminpanel/js',
	paths: { //path to files
		jquery: "jquery",
		bootstrap: "bootstrap.min",
		custom: "custom",
		adminjs: "adminjs",
		user: "user",
		validation: "validation",

	},
	shim: { //dependencies
		"bootstrap": ['jquery'],
		"custom": ['bootstrap'],
		"adminjs": ['custom'],
		"user": ["adminjs"],
                "validation": ["user"],

	}
});

requirejs(["jquery",
	"bootstrap",
	"custom",
	"adminjs",
	"user",
        "validation",
], function ($) {
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
						$("#profile-picture1").val(response.data);
                                          
						$('#profilePic').css('background-image', 'url(' + response.data + ')');
						// previewImage(this, $(".profilePic"));
					}

					$("#csrf_token").val(response.csrf_token);
				}
			})

		});                  
                  /*Validation for Add merchant*/  
                  var $Admin_Name = $("#Admin_Name"),
			$email = $("#email"),
			$mobileNumber = $("#mobile-number"),

			errorMessage = [ //corresponding error messages in sequence
				"Admin_Name is required and must contain only letters.",
				"Enter valid email.",
                                "Enter valid 10 digit mobile number",
 
			];
                        //On Submit handler
		$("#editadmin").on("submit", function () {
			//elements to validate without the '#'
			var element = [
				"Admin_Name", "email", "mobile-number"
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


	
                        	return true;
		}); /* on submit */

		/* First name validation */
		$Admin_Name.on("blur", function () {
			onBlurChecks($Admin_Name,
				validateName($Admin_Name.val().trim()),
				$Admin_Name.closest(".input-holder").siblings(".error"),
				errorMessage[0]);
		});

		/* email on blur validation  */
                
		$email.on("blur", function () {
			var email = $email.val().trim();
                         var userid=$("#userid").val();
				$current = $(this);
			if (validateEmail(email)) {
				userData = {
					email: email,
					csrf_token: $("#csrf_token").val(),
                                        userid:userid,
				}
				$.ajax({
					method: "post",
					data: userData,
					dataType: "json",
					url: "req/check-edit-email-exists",
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
			var mobile = $mobileNumber.val().trim();
                           var userid=$("#userid").val();
				$current = $(this);
			if (validateMobileNumber(mobile)) {
				userData = {
					mobile_number: mobile,
                                        csrf_token: $("#csrf_token").val(),
                                        userid:userid,
				}
				$.ajax({
					method: "post",
					data: userData,
					dataType: "json",
					url: "req/check-edit-mobile-exists",
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


});


