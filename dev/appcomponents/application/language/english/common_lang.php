<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
  | -------------------------------------------------------------------
  |  Error or success messages
  | -------------------------------------------------------------------
 */
/*
 * ADMIN ERROR MSG
 * 
 */
$lang['ACCOUNT_DEACTIVATED'] = "<div class='alert alert-danger'>Your account has been deactivated please contact admin.</div>";
$lang['NOT_ADMIN'] = "<div class='alert alert-danger'>Please try with other account you are not admin user.</div>";
$lang['INVALID_USER'] = "<div class='alert alert-danger'>Either username or password is incorrect.</div>";

$lang['EMAIL_ID_NOT_REGISTERED'] = "<div class='alert alert-danger'>Email id you have provided to us is not registered at Delivery Service.</div>";
$lang['SUCCESS_EMAIL_SEND'] = "<div class='alert alert-success'>A reset link has been send to your email id registered with us.</div>"; 
$lang['TRY_AGAIN'] = "<div class='alert alert-danger'>Please try again.</div>";
$lang['INVALID_EMAIL_ID'] = "<div class='alert alert-danger'>Please enter valid email id</div>";
$lang['INVALID_USER_ACESS'] = "<div class='alert alert-danger'>Invalid user access password cannot be reset.</div>";
$lang['PASSWORD_SUCCESS'] = "Password updated succesfully redirecting to login page.";

$lang['PASSWORD_UNSUCCESS'] = "Password not updated succesfully please try again";
$lang['EMPTY_PASSWORD'] = "Please enter valid password";

$lang['ERROR_ADMIN_LOGIN'] = "Please login with other account as you are not admin";
$lang['PROFILE_SUCCESS'] = "<div class='alert alert-success'>Profile updated successfully.</div>";
$lang['PROFILE_UNSUCCESS'] = "<div class='alert alert-danger'>Sorry your Profile is not updated.</div>";
$lang['EMPTY_FIELDS'] = "<div class='alert alert-danger'>Please fill all the fields.</div>";
$lang['FILE_ERROR_SIZE'] = "<div class='alert alert-danger'>Please upload png/jpg file only max 5mb.</div>";

$lang['PASSWORD_SUCCESS'] = "<div class='alert alert-success'>Password updated successfully.</div>";
$lang['INVALID_PASSWORD'] = "<div class='alert alert-danger'>Invalid old password.</div>";
$lang['INVALID_AJAX_REQUEST']= "Invalid ajax request";
$lang['EMPTY_DATA']= "Search filed is empty";
$lang['Invalid_Header_key'] = 'Invalid header key';
$lang['Method_Not_Found'] = 'Method not found';
$lang['Required_Parameter_Is_Missing'] = 'Required parameter is missing';
$lang['signin_opt_msg'] = 'OTP sent your registered mobile number';
$lang['Email_Already_Registered'] = 'Email address is already registered';
$lang['Phone_Already_Registered'] = 'Phone No is already registered';
$lang['Invalid_OTP'] = 'Invalid OTP';
$lang['Invalid_Credentials'] = 'Either email or password is Incorrect';
$lang['Email_id_not_registered_on_web'] = 'Provided email id is not registered with us.';
$lang['DATA_NOT_FOUND']= "No data found";
$lang['Email_id_not_registered_on_app'] = 'Email id not registered on app';
$lang['Phone_no_not_registered_on_app'] = 'Phone No not registered on app';
$lang['Otp_send_on_registered_email_id'] = 'OTP send on register email id';
$lang['First_verify_your_phone_number'] = 'First verify your phone number';
$lang['Successful_verfied'] = 'Successful Verfied';
$lang['INVALID_ACCESS_TOKEN'] = 'Access token is invalid';
$lang['USER_STATUS_NOT_ACTIVE'] = 'User not active';
$lang['HEADER_MISSING'] = 'Headers are missing';
$lang['EMPTY_DATA'] = 'No Data Found!';
$lang['PROBLEM_IN_SAVE'] = 'Problem in saving data, Please try again!';
$lang['REQUEST_SUCCESS'] = 'Load request save successfully!';
$lang['REQUEST_MESSAGE_TO_COMPANY'] = '%s driver request for load id %s';
$lang['REQUEST_STATUS_CHANGE_BY_DRIVER'] = '%s driver change status of load id %s';
$lang['WRONG_ASSOCIATION_CODE'] = 'Wrong assocition Code!';
$lang['COMPANY_ASSOCIATED_SUCCESSFULLY'] = 'Company associated successfully';
$lang['GHOST_MODE_SUCCESS'] = 'Ghost mode updated Successfully!';
$lang['FEEDBACK_SUCCESS'] = 'Feedback posted Successfully!';
$lang['WRONG_OLD_PASSWORD'] = 'You entered wrong old password.';
$lang['PASSWORD_RESET_SUCCESSFULLY'] = 'Password changed successfully';
$lang['RATING_SUCCESS'] = 'Ratings saved successfully';
$lang['INSERT_SUBSCRIPTION'] = 'Subscription created successfully';
$lang['SUBSCRIPTION_REQUEST_INSERT_SUCCESSFULLY'] = 'Subscription request insert sucessfully';
$lang['TRIP_INSERT_SUCCESSFULLY'] = 'Trip insert sucessfully';
$lang['TRIP_UPDATED_SUCCESSFULLY'] = 'Trip updated sucessfully';
$lang['TRIP_ADDED_SUCCESSFULLY'] = 'Loaded added to trip sucessfully';
$lang['TRIP_LOAD_REMOVED_SUCCESSFULLY'] = 'Loaded removed to trip sucessfully';
$lang['TRIP_REMOVED_SUCCESSFULLY'] = 'Trip removed sucessfully';

/* --------------------------------------success-------------------------------------------------- */

$lang['Success'] = 'Success';
$lang['SERVER_ERROR'] = 'Server Error';
$lang['Registration_successful'] = 'Registration Successful';
$lang['INSERTION_SUCCESS'] = "New load data has been inserted succesfully Do you wish to continue adding load?";
$lang['INSERTION_UNSUCCESS'] = "New load data has not been inserted succesfully";
$lang['PASSWORD_RESET_SUCCESS'] = "<div class='alert alert-success'>Password reset successful.</div>";
$lang['PASSWORD_NOT_MATCH'] = "<div class='alert alert-danger'>Password not match.</div>";
$lang['LINK_EXPIRE'] = "<div class='alert alert-danger'>Link expired.</div>";
$lang['INVALID_LINK'] = "<div class='alert alert-danger'>Not a valid link.</div>";
$lang['EMPTY_GET_DATA'] = "Data not found";
$lang['INVALID_GET_DATA'] = "Data not valid";
$lang['UNSUCCESS_CITY_LIST'] = "City list not found";
$lang['EMPTY_POST_DATA'] = "data not found";
$lang['INVALID_AJAX_REQUEST'] = "Invalid ajax request";
$lang['DATA_NOT_FOUND'] = "Failed to update information user not found";
$lang['UNSUCCESS'] = "failed";
$lang['VALID_INVITE_CODE'] = "Valid Invite Code.";
$lang['INVALID_INVITE_CODE'] = "Invalid Invite Code.";
$lang['USER_BLOCKED_ERROR'] = "User blocked by admin";
$lang['USER_DELETED_ERROR'] = "User deleted by admin";
$lang['LOCATION_UPDATED'] = "Location updated successfully";
$lang['INSERTION_SUCCESS_UPDATE'] = "Load updation successfull. Do you wish to continue adding load?";
$lang['NOT_APPLICABLE'] ="Load must be either rejected or cancelled to re-asign";
$lang['NEW_DRIVER_ADDED'] = "New driver has successfully added";
$lang['NEW_DRIVER_UPDATED'] = "Driver has been successfully updated";
$lang['FILE_UPLOAD_SUCCESS'] = "File successfully uploaded";
$lang['FILE_UPLOAD_FAIL'] = "File upload failed";
$lang['FILE_UPLOAD_ERROR'] ="Something went wrong when saving the file, please try again.";
$lang['USER_NOT_REGISTERED_ON_STRIPE'] = 'User is not registered on stripe portal';

// Password Reset
$lang['password_reset_success'] = 'Success:Your password has been reset';
$lang['try_again'] = 'Please try again in a while';
$lang['password_already_reset'] = 'You have already reset you password,try login';