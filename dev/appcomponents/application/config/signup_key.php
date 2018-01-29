<?php

// POSSIBLE KEYS DURING REGISTRATION API END ADD KEY HERE AS WELL AS IN DB

$config['sign_keys'] = [
    "first_name",
    "middle_name",
    "last_name",
    "email",
    "gender",
    "biography",
    "dob",
    "age",
    "phone",
    "password",
    "username",
    "image",
    "device_id",
    "device_token",
    "ipaddress",
    "device_model",
    "imei",
    "os_version",
    "platform",
    "network",
    "app_version",
    "longitude",
    "latitude",
    "country_code",
    "region",
    "city",
    "postal_code"
];


/*
 *  TYPE OF LOGINS MULTIPLE DEVICE LOGIN , SINGLE DEVICE LOGIN , LIMITED DEVICE LOGIN 
 *    
 *    USE LOGIN_TYPE : SINGLE 1,MULTIPLE 2,LIMITED TO SET THE TYPE OF LOGIN 3 
 * 
 *    FOR LIMITED LOGIN DEFINE THE LIMIT VALUE OF LOGINS
 *    
 */



$config['LOGIN_TYPE'] = 1;
$config['LOGIN_LIMIT'] = 1;

