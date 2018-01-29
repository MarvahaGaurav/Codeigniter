var controller  = window.location.pathname.split('/')[3];
var action  = window.location.pathname.split('/')[4];

var _validFileExtensionsImage = [".jpg",".png",'.jpeg','.gif','.bmp'];
var _validFileExtensionsDoc = [".doc", ".docx", ".pdf"];    
function ValidateSingleInput(oInput, _validFileExtensions,id) {
    if (oInput.type == "file") {
        var sFileName = oInput.value;
         if (sFileName.length > 0) {
            var blnValid = false;
            for (var j = 0; j < _validFileExtensions.length; j++) {
                var sCurExtension = _validFileExtensions[j];
                if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
                    blnValid = true;
                    break;
                }
            }
             
            if (!blnValid) {
                //alert("Sorry, " + sFileName + " is invalid, allowed extensions are: " + _validFileExtensions.join(", "));
                $('#image-error').empty().text("Sorry, " + sFileName + " is invalid, allowed extensions are: " + _validFileExtensions.join(", "));
                oInput.value = "";
                return false;
            }
        }
    }
    return true;
}


/**
 * 
 * @ code to validate uploaded image.
 */ 
var loadFile_signup = function(event,id, oInput) {
	var return_data = ValidateSingleInput(oInput, _validFileExtensionsImage,id);
	if (return_data) {
		var output = document.getElementById(id);
        $('#'+id).css('background-image', 'url(' + URL.createObjectURL(event.target.files[0]) + ')');
	}
};
/**
 * 
 *@description This is used to fadeout the ajax loader from page. 
 * 
 */
 
$(document).ready(function () {
	$('#pre-page-loader').hide();
});

/**
 * @description This code of jquery is used to show ckeditor on the add & edit cms pages.
 * 
 */
  
  $(document).ready(function () {
	if(controller==='cms' && (action==='add' ||action==='edit') ){  
		CKEDITOR.replace('page_desc');
	}
  });


/**
 * forgot password validation
 */

$('#forgot').click(function (event) {
    var arr = [];
    var f = 0;
    var email = $("#email").val();
    var emailptrn = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
   var  csrf_token = $("#csrf_token").val();
    if (email.length == 0)
    {
        document.getElementById('emails').innerHTML = "Please fill this field";
        $("#email_error").addClass("commn-animate-error");
        f = 1;
        arr.push('email')
        setTimeout(function () {
            $('#emails').text('');
        }, 5000);
    } else if (email.match(emailptrn)) {
        $.ajax({
            type: "post",
            url: domain + '/admin/Admin/check_email_avalibility',
            data: {'email': email,'csrf_token':csrf_token},
            async: false,
            success: function (result) {
                console.log(result);
 
                var Obj = JSON.parse(result);
                if (Obj.code == 201) {
                    $('#incorrectemail').html('Email Exist');
                    $("#email_error").addClass("commn-animate-error");
                    setTimeout(function () {
                        $('#incorrectemail').text('');
                    }, 3000);

                } else if (Obj.code == 200) {
                    $('#errmsg2').html('Email Doesnot Exist');
                    $("#email_error").addClass("commn-animate-error");

                    setTimeout(function () {
                        $('#errmsg2').text('');
                    }, 3000);
                    f = 1;
                }
                  $("#csrf_token").val(Obj.csrf_token);
            }
        });

    } else {
        document.getElementById('emails').innerHTML = "Please enter valid email";
        $("#email_error").addClass("commn-animate-error");
        f = 1;
        arr.push('email')
        setTimeout(function () {
            $('#emails').text('');
        }, 5000);
    }

    if (f == 1) {
        $('#' + arr[0]).focus();
        return false;
    } else {
        $('#forgetpass').submit();
    }
});

//$('#resetbtn').click(function (event) {
//
//    $('#resetform').submit();
//});


$(document).ready(function () {
    $('#resetbtn').click(function (event) {
        var arr = [];
        var f = 0;
        var new_pass = $("#new_pass").val();
        var con_pass = $("#con_pass").val();
        var token = $("#token").val();
      //  var passptr = /^(?=.*\d)(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z]).{8,}$/;
        var passptr = /^.{6,}$/;
        if (new_pass.length == 0)
        {
            document.getElementById('new_pass1').innerHTML = "Please fill this field";
            $("#passerror").addClass("commn-animate-error");
            return false;
        } else if (!new_pass.match(passptr)) {
            document.getElementById('new_pass1').innerHTML = "Password must be atleast 6 character";
            $("#passerror").addClass("commn-animate-error");
            return false;

        }
        if (con_pass.length == 0)
        {
            document.getElementById('con_pass1').innerHTML = "Please fill this field";
            $("#conpassreq").addClass("commn-animate-error");
            return false;
        } else if (!new_pass.match(passptr)) {
            document.getElementById('con_pass1').innerHTML = "Password must be atleast 6 characters ";
            $("#conpassreq").addClass("commn-animate-error");
            return false;

        }

        if (new_pass != con_pass) {
            document.getElementById('con_pass1').innerHTML = "Confirm password does not match";
            $("#conpassreq").addClass("commn-animate-error");
            return false;
        }

        if (f == 1) {
            $('#' + arr[0]).focus();
            return false;
        }else{
          //alert('hdfh');
            $('#resetform').submit();
        }

    }
    );
});

/*
 * Login validation
 */
$('#login').click(function (event) {

    var arr = [];
    var f = 0;
    var user_email = $("#useremail").val();
    var user_password = $("#userpassword").val();

    //var passptr = /^(?=.*\d)(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z]).{8,}$/;
    var emailptrn = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    var passptr = /^.{6,}$/;
    if (user_email.length == 0)
    {

        document.getElementById('email1').innerHTML = "Please fill this field";
        $("#email_error").addClass("commn-animate-error");
        return false;
    } else if (!user_email.match(emailptrn)) {
        document.getElementById('email1').innerHTML = "Please enter valid email";
        $("#email_error").addClass("commn-animate-error");
        return false;
    }
    if (user_password.length == 0) {
        document.getElementById('password1').innerHTML = "Please enter password";
        $("#passworderr").addClass("commn-animate-error");
        return false;

    } else if (!user_password.match(passptr)) {
        document.getElementById('password1').innerHTML = "Password must be atleast 6 characters";
        $("#passworderr").addClass("commn-animate-error");
        return false;
    }

    if (f == 1) {
        $('#' + arr[0]).focus();
        return false;

    } else {
        $('#adminlogin').submit();
    }
});


$('.removemessage').keyup(function () {
    $(".form-field-wrap").removeClass("commn-animate-error");
});

$('#email').keyup(function () {
    $("#emails").removeClass("commn-animate-error");
});

//-----------------------------------------------------------------------
 $(function(){
		var flag= false;
		$('.filter').each(function(){
			
			if($(this).val()){
				flag =true;
			}
			
		});
		
		if(flag==false){
			$('#filterbtn').prop('disabled', true);
			$('#resetbtn').prop('disabled', true);
		}else{
			$('#filterbtn').prop('disabled', false);
			$('#resetbtn').prop('disabled', false);
		}
	
   });
   
  $(document.body).on("change",".filter",function () {
		var flag= false;
		$(this).each(function(){
			
			if($(this).val()){
				flag=true;
			}
			
		});
		if(flag==true){
			$('#filterbtn').prop('disabled', false);
			$('#resetbtn').prop('disabled', false);
		}else{
			$('#filterbtn').prop('disabled', true);
			$('#resetbtn').prop('disabled', true);
		}
   }); 
   
   $(document.body).on("blur",".filtertxt",function () {
		var flag= false;
		$(this).each(function(){
			
			if($(this).val()){
				flag=true;
			}
			
		});
		if(flag==true){
			$('#filterbtn').prop('disabled', false);
			$('#resetbtn').prop('disabled', false);
		}else{
			$('#filterbtn').prop('disabled', true);
			$('#resetbtn').prop('disabled', true);
		}
   }); 
   


//-----------------------------------------------------------------------
/**
  *
  * @name starting to implement jquery code when document loaded.
  *
  **/
   $(function(){
	  
	  if($('.search-box').val()){ 
		   
		   if ($('.search-box').val().length) {
						$('.srch-close-icon').show();
						$('.search-icon').hide();
		   } else {
						$('.srch-close-icon').hide();
						$('.search-icon').show();      
		   }
	  }
   });
   
   
   
   $(document.body).on("keyup",".search-box",function () {
		
			   if ($(this).val().length>0) {
					//$('.srch span').removeClass('search-icon'); 		
					  // $('.srch span').addClass('search-close-icon');
					  $('.srch-close-icon').show();
					   $('.search-icon').hide();
			   } else {
					  // $('.srch span').removeClass('search-close-icon');
					   //$('.srch span').addClass('search-icon'); 
					   $('.srch-close-icon').hide();
					   $('.search-icon').show(); 
			   }
   });




/**
 * 
 * @returns {undefined}
 * 
 */
 function pageCountForm(){
     $('#page_count_form').submit();
 }
//----------------------------------------------------------------------- 
 /**
 * @name getStates
 * @description Function to get States list as per the country.
 */
 function getStates(value,id){
	 
	 var csrf  = $('#csrfToken').val();
     if(value){
           $.ajax({
            method: "GET",
            url: baseUrl+"admin/AjaxUtil/getStatesByCountry",
            data:{id:value,csrf_token:csrf}
        }).done(function( msg ) {
              msg=JSON.parse(msg);
              $('#'+id).empty();
              $("#"+id).append('<option value=>Select State</option>');
                $.each(msg, function(i, item) {
                     $('#'+id).append($('<option>', {
                        value: item.id,
                        text : item.name,
                    }));
                });
                $('#'+id).selectpicker('refresh');
        });
    }
 }
 
 //----------------------------------------------------------------------
 /**
 * @name getCities
 * @description Function to get cities list as per the states.
 */
 function getCities(value,id){
	 
	 var csrf  = $('#csrfToken').val();
     if(value){
           $.ajax({
            method: "GET",
            url: baseUrl+"admin/AjaxUtil/getCityByState",
            data:{id:value,csrf_token:csrf}
        }).done(function( msg ) {
              msg=JSON.parse(msg);
              $('#'+id).empty();
              $("#"+id).append('<option value=>Select City</option>');
                $.each(msg, function(i, item) {
                     $('#'+id).append($('<option>', {
                        value: item.id,
                        text : item.name,
                    }));
                });
                $('#'+id).selectpicker('refresh');
        });
    }
 }
 //----------------------------------------------------------------------
 /**
  * @name blockUser
  * @description This method is used to show block user modal.
  * 
  */
 
 function blockUser(type,status,id,url,msg,action){
	 
	 $('#new_status').val(status);
	 $('#new_id').val(id);
	 $('#new_url').val(url);
	 $('.modal-para').text(msg);
	 $('#action').text(action);
	 $('#for').val(type);
	 $('#myModal-block').modal('show');
 }
 
 //----------------------------------------------------------------------
 /**
  * @name blockUser
  * @description This method is used to show block user modal.
  * 
  */
 
 function logoutUser(){
	 
	 $('#myModal-logout').modal('show');
 }
 
 
//-----------------------------------------------------------------------
 /**
  * @name changeStatusToBlock
  * @description This method is used to block the user.
  * 
  */
 
 function changeStatusToBlock(type,status,id,url){
	var csrf  = $('#csrf').val();
	$.ajax({
            method: "POST",
            url: baseUrl+url,
            data:{type:type,new_status:status,id:id,csrf_token:csrf},
            beforeSend:function(){
				$('#pre-page-loader').fadeIn();
				$('#myModal-block').modal('hide');
			},
            success:function(res){
				$('#pre-page-loader').fadeOut();
				res=JSON.parse(res);
				var csrf  = $('#csrf').val(res.csrf_token);
				if(res.code===200){
					
					if(status==2){
						$('#error').empty().append(string.successPrefix+string.block_success+string.successSuffix);
						$('#unblock_'+res.id).show();
						$('#block_'+res.id).hide();
						$('#status_'+res.id).empty().text('Blocked');
					}else{
						$('#error').empty().append(string.successPrefix+string.unblock_success+string.successSuffix);
						$('#block_'+res.id).show();
						$('#unblock_'+res.id).hide();
						$('#status_'+res.id).empty().text('Active');
					}
				}
			},
			error:function(xhr){
				alert("Error occured.please try again");
				$('#pre-page-loader').fadeOut();
			}
        });
 } 
 //----------------------------------------------------------------------
 /**
  * @name deleteUser
  * @description This method is used to show delete user modal.
  * 
  */
 
 function deleteUser(type,status,id,url,msg){
	 
	 $('#new_status').val(status);
	 $('#new_id').val(id);
	 $('#new_url').val(url);
	 $('.modal-para').text(msg);
	 $('#for').val(type);
	 $('#myModal-trash').modal('show');
 }
//-----------------------------------------------------------------------
 /**
  * @name changeStatusToDelete
  * @description This method is used to delte the user.
  * 
  */
 
 function changeStatusToDelete(type,status,id,url){
	var csrf  = $('#csrf').val();
	$.ajax({
            method: "POST",
            url: baseUrl+url,
            data:{type:type,new_status:status,id:id,csrf_token:csrf},
            beforeSend:function(){
				$('#pre-page-loader').fadeIn();
				$('#myModal-trash').modal('hide');
			},
            success:function(res){
				$('#pre-page-loader').fadeOut();
				res=JSON.parse(res);
				var csrf  = $('#csrf').val(res.csrf_token);
				if(res.code===200){
					$('#remove_'+res.id).remove();
					var tb_length = $('#table_tr > tr').length;
					if(tb_length==0){
						$('#table_tr').html('<tr><td colspan="9">No result found.</td></tr>');
					}
				}
			},
			error:function(xhr){
				alert("Error occured.please try again");
				$('#pre-page-loader').fadeOut();
			}
        });
 }
 
 //-----------------------------------------------------------------------
  /**
   *@Description Here is the methods starts for the form validations in admin using jquery validator. 
   * 
   */
   $(document).ready(function () {
		
		$.each($.validator.methods, function (key, value) {
           $.validator.methods[key] = function () {
               if(arguments.length > 0) {
                   arguments[0] = $.trim(arguments[0]);
               }

               return value.apply(this, arguments);
           };
		});
		
		// error message
	   $.validator.setDefaults({
			
			ignore: ':not(select:hidden, input:visible, textarea:visible):hidden:not(:checkbox)',

			errorPlacement: function (error, element) {
				if (element.hasClass('selectpicker')) {
					error.insertAfter(element);
				}
				else if (element.is(":checkbox")) {
				   // element.siblings('span').hasClass('.check_error_msg').append(error);

				   error.insertAfter($('.check_error_msg'));
				}
				 else {
					error.insertAfter(element);
				}
				/*Add other (if...else...) conditions depending on your
				* validation styling requirements*/
			}
		});
		//custom methods
    
		$.validator.addMethod("noSpace", function(value, element) {
			return value == '' || value.trim().length != 0; 
		  }, "");
		  
	    $.validator.addMethod("searchText", function(value, element) {
                             return value.replace(/\s+/g, '');
                            }, "");	  
		
		/**
		 * @name validate admin password change form
		 * @description This method is used to validate admin change password form.
		 * 
		 */
		 $("#password_change_form").validate({
				errorClass: "alert-danger",
				rules: {
					oldpassword: {
						required: true,
						
					},
					password: {
						required: true,
						minlength:6,
						maxlength:50
					},
					confirm_password: {
						required: true,
						minlength:6,
						maxlength:50,
						equalTo:"#password"
					}
				},
				messages:{
					oldpassword:string.oldpasswordEmpty,
					password:string.newpasswordEmpty,
					confirm_password:{
									required:string.confirmpasswordEmpty,
									equalTo:string.passwordnotmatch
						}
					
				},
				submitHandler: function (form) {
					form.submit();
				}
			});
			
		
		/**
		 * @name: add cms content form 
		 * @description: Thie function is used to validate admin add content form in cms.
		 */
		 $("#cms_add_form").validate({
				ignore: [],
				debug: false,
				errorClass: "alert-danger",
				rules: {
					title: {
						required: true,
					},
					page_desc: {
							required: function ()
								{
									CKEDITOR.instances.page_desc.updateElement();
								},
					},
					status: {
						required: true,
					}
				},
				/* use below section if required to place the error*/
				errorPlacement: function (error, element)
				{
					if (element.attr("name") == "page_desc")
					{
						element.next().css('border', '1px solid #a94442');
						error.insertBefore("textarea#page_desc");
					} else {
						error.insertBefore(element);
					}
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
	 $("#version_add_form").validate({
			errorClass: "alert-danger",
			rules: {
				name: {
					required: true,
				},
				title: {
					required: true,
				},
				desc: {
					required: true,
				},
				platform: {
					required: true,
				},
				update_type: {
					required: true,
				},
				current_version: {
					required: true,
				}
			},
			submitHandler: function (form) {
				form.submit();
			}
		}); 	
	/**
	 * @name common search for admin
	 * 
	 * 
	 */
	 $("#admin_search_form").validate({
        errorPlacement: function(error, element) {},
                rules: {
                      search:{
                          searchText:true,
                          required:{
                                    depends:function(){
                                         if($.trim($(this).val().length)==0){
                                            //$('form#admin_search_from :input[type=text]').empty().css('border-color','#ff0000');
                                            $('#searchuser').empty().css('border-color','#a94442');
                                            return false;
                                        }else{
                                            $.trim($(this).val());
                                            return true;
                                        }
                                    }
                                }
                            }
                },
                submitHandler: function(form) {
                        form.submit();
                 }
    });	
    
    	/**
		 * @name validate admin password change form
		 * @description This method is used to validate admin change password form.
		 * 
		 */
		 $("#editadminprofile1").validate({
				errorClass: "alert-danger",
				rules: {
					Admin_Name: {
						required: true,
						
					},
					email: {
						required: true,
						email:true
					},
					mobile_number: {
						required: true,
					},
				},
				submitHandler: function (form) {
					form.submit();
				}
			});
    	
	
   });
