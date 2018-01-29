<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
  |--------------------------------------------------------------------------
  | Display Debug backtrace
  |--------------------------------------------------------------------------
  |
  | If set to TRUE, a backtrace will be displayed along with php errors. If
  | error_reporting is disabled, the backtrace will not display, regardless
  | of this setting
  |
 */
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
  |--------------------------------------------------------------------------
  | File and Directory Modes
  |--------------------------------------------------------------------------
  |
  | These prefs are used when checking and setting modes when working
  | with the file system.  The defaults are fine on servers with proper
  | security, but you may wish (or even need) to change the values in
  | certain environments (Apache running a separate process for each
  | user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
  | always be used to set the mode correctly.
  |
 */
defined('FILE_READ_MODE') OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE') OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE') OR define('DIR_WRITE_MODE', 0755);
define('AUTH_PASS', '12345');
define('AUTH_USER', 'admin');
define('PASSWORD_REGEX', '/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{8,20}$/');
/*
  |--------------------------------------------------------------------------
  | File Stream Modes
  |--------------------------------------------------------------------------
  |
  | These modes are used when working with fopen()/popen()
  |
 */
defined('FOPEN_READ') OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE') OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE') OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE') OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE') OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE') OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT') OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT') OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
  |--------------------------------------------------------------------------
  | Exit Status Codes
  |--------------------------------------------------------------------------
  |
  | Used to indicate the conditions under which the script is exit()ing.
  | While there is no universal standard for error codes, there are some
  | broad conventions.  Three such conventions are mentioned below, for
  | those who wish to make use of them.  The CodeIgniter defaults were
  | chosen for the least overlap with these conventions, while still
  | leaving room for others to be defined in future versions and user
  | applications.
  |
  | The three main conventions used for determining exit status codes
  | are as follows:
  |
  |    Standard C/C++ Library (stdlibc):
  |       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
  |       (This link also contains other GNU-specific conventions)
  |    BSD sysexits.h:
  |       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
  |    Bash scripting:
  |       http://tldp.org/LDP/abs/html/exitcodes.html
  |
 */
defined('EXIT_SUCCESS') OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR') OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG') OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE') OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS') OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT') OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE') OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN') OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX') OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code
//------------------DIRECTORY UPLOAD CONSTANTS ------------------//
define("UPLOAD_IMAGE_PATH", getcwd() . "/uploads/");
define("IMAGE_PATH",'http://'.$_SERVER['HTTP_HOST'] . "/dev/appcomponents/uploads/");
define("UPLOAD_THUMB_IMAGE_PATH", getcwd() . "/uploads/thumbs/");
define("THUMB_IMAGE_PATH", 'http://'.$_SERVER['HTTP_HOST'] . '/dev/appcomponents/uploads/thumbs/');
define('DIR_FOLDER_NAME', 'uploads');
define('SENDER_EMAIL_ID', 'jay.pandey45@gmail.com');



//-----------------USER STATUS TYPE ---------------//
define('ACTIVE_USER', 1);
define('INACTIVE_USER', 215);

define('BASE_URL', (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" . '/');
define('BASE_URL_FILE', $_SERVER['HTTP_HOST']);





//----------------ERROR MESSAGE CODE FOR CLIENT SIDE VALIDATIONS ---------------------------//

define('SUCCESS', 200);
define('TRY_AGAIN', 501);

define('PARAM_REQ', 418);
define('SUCCESS_REGISTRATION', 200);

define('INVALID_EMAIL', 419);
define('EMAIL_ALREADY_EXIST', 420);

define('ERROR_UPLOAD_FILE', 421);
define('INVALID_DATE_FORMAT', 423);

define('INVALID_MAX_LENGTHEMAIL', 425);
define('INVALID_PASSWORD_FORMAT', 426);

define('INVALID_LOGIN', 410);
define('SUCCESS_LOGIN', 200);

define('MISSING_HEADER', 207);
define('INVALID_HEADER', 206);
/*
 * Email Contants
 */
define('EMAIL_SEND_SUCCESS', 200);
define('EMAIl_SEND_FAILED', 211);


define('ERROR_INSERTION', 510);


// ===================login type constants =========================//
define('SINGLE_LOGIN', 1);
define('MULTIPLE', 2);
define('LIMITED', 3);


/*
 * Reset password Codes
 */
define('PASSWORD_ALREADY_SET', 301);
define('EMAIL_NOT_EXIST', 302);






