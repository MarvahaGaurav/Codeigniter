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

defined('BASE_URL') OR define('BASE_URL', $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST']).'/sguide'; // no errors
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

/* HEADER STATUS CONSTANTS */
defined("UNAUTHORIZED_ACCESS") OR define("UNAUTHORIZED_ACCESS", 401);
defined("NOT_AUTHENTICATED") OR define("NOT_AUTHENTICATED", 403);
defined("ACCESS_TOKEN_NOT_SET") OR define("ACCESS_TOKEN_NOT_SET", 406);

/* FIELD DOESNT MATCH */
defined("OLD_PASSWORD_MISMATCH") OR define("OLD_PASSWORD_MISMATCH", 490);
defined("PASSWORD_MISMATCH") OR define("PASSWORD_MISMATCH", 491);
defined("NEW_PASSWORD_SAME") OR define("NEW_PASSWORD_SAME", 492);

/* User status */
defined('ACTIVE') OR define('ACTIVE', 1);
defined('BLOCKED') OR define('BLOCKED', 2);
defined('DELETED') OR define('DELETED', 3);
defined('INACTIVE') OR define('INACTIVE', 0);
defined('DEFAULT_DB_DATE_TIME_FORMAT') OR define('DEFAULT_DB_DATE_TIME_FORMAT', date("Y-m-d H:i:s"));
defined('COOKIE_EXPIRY_TIME') OR define("COOKIE_EXPIRY_TIME", 86400 * 7);

// device type

defined('ANDROID') OR define('ANDROID', 1);
defined('IPHONE') OR define('IPHONE', 2);

//update type
defined('NORMAL') OR define('NORMAL', 1);
defined('SKIPPABLE') OR define('SKIPPABLE', 2);
defined('FORCEFULLY') OR define('FORCEFULLY', 3);

defined('YES') OR define('YES', 1);
defined('NO') OR define('NO', 0);

/*
 * Basic Auth UserName and Password
 */
define('AUTH_PASS', '12345');
define('AUTH_USER', 'admin');
/*
 * Upload Directories Constants
 */
//echo getcwd(); die;
define("UPLOAD_PATH", "/public/uploads/");
define("UPLOAD_PATH_LOCAL", "/sguide/public/uploads/");
define("PROJECT_NAME", "Smart Guide");
define("UPLOAD_IMAGE_PATH", getcwd() . UPLOAD_PATH);
define("IMAGE_PATH", 'http://' . $_SERVER['HTTP_HOST'] . UPLOAD_PATH);
define("UPLOAD_THUMB_IMAGE_PATH", getcwd() . UPLOAD_PATH . "thumbs/");
define("THUMB_IMAGE_PATH", 'http://' . $_SERVER['HTTP_HOST'] . UPLOAD_PATH . "thumbs/");
define("DEFAULT_IMAGE", '/public/images/default.png');
define("TIMEZONE", 'UTC');
/* AWS S3  */
defined("AWS_ACCESSKEY") or define('AWS_ACCESSKEY', 'AKIAIGTT2CNXI3KAGXSQ');
defined('AWS_SECRET_KEY') or define('AWS_SECRET_KEY', '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');

/* image constants */
defined("AMAZONS3_BUCKET") or define("AMAZONS3_BUCKET", "appinventiv-development");

/* Content Type */
defined("CONTENT_TYPE_IMAGE") OR define("CONTENT_TYPE_IMAGE", 1);
defined("CONTENT_TYPE_VIDEO") OR define("CONTENT_TYPE_VIDEO", 2);
defined("CONTENT_TYPE_PDF") OR define("CONTENT_TYPE_PDF", 3);

//----------------ERROR MESSAGE CODE FOR CLIENT SIDE VALIDATIONS ---------------------------//

define('SUCCESS_CODE', 200);
define('TRY_AGAIN_CODE', 201);

define('NO_DATA_FOUND', 202);

define('PARAM_REQ', 418);

define('INVALID_EMAIL', 419);
define('EMAIL_ALREADY_EXIST', 420);
define('INVALID_REQUEST_ID', 429);

define('ERROR_UPLOAD_FILE', 421);
define('INVALID_DATE_FORMAT', 423);

define('INVALID_OTP', 424);

define('INVALID_MAX_LENGTHEMAIL', 425);
define('INVALID_PASSWORD_FORMAT', 426);

define('INVALID_USERID', 427);
define('OTP_NOT_VERIFIED', 428);

define('INVALID_LOGIN', 410);
define('SUCCESS_LOGIN', 200);

define('MISSING_HEADER', 207);
define('INVALID_HEADER', 206);
/*
 * Email Contants
 */
define('EMAIL_SEND_SUCCESS', 200);
define('EMAIl_SEND_FAILED', 211);

/*
 * Access Token
 */
define('INVALID_ACCESS_TOKEN', 100);
define('ACCESS_TOKEN_EXPIRED', 101);
define('MISSING_PARAMETER', 102);

// ===================login type constants =========================//
define('IS_SINGLE_DEVICE_LOGIN', 0);
define('LIMITED', 3);
define('ACCOUNT_BLOCKED', 101);
define('INVALID_CREDENTIALS', 102);
define('ACCOUNT_INACTIVE', 103);
/*
 * Forgot Password Codes
 */
define('EMAIL_NOT_EXIST', 302);
/*
 * Reset password Codes
 */
define('PASSWORD_ALREADY_SET', 301);

define('RECORD_NOT_EXISTS', 307);

/*
 * Friend Request Codes
 */
define('REQUEST_ALREADY_SENT', 500);
define('REQUEST_ALREADY_RECEIVED', 501);
/*
 * Review already exist
 */

define('MULTIPLE_REVIEW_ALLOWED', 1);
define('REVIEW_ALREADY_EXISTS', 505);

/*
 * Following Request Codes
 */

define('ALREADY_FOLLOWING', 506);
/*
 * Already Favorite
 */
define('ALREADY_FAVORITE', 507);

/*
 * Push Type
 */
define('PUSH_SOUND', 'beep.mp3');
define('REQUEST_PUSH', 1);
define('REQUEST_ACCEPT_PUSH', 2);
define('FAVORITE_PUSH', 3);
define('FOLLOW_PUSH', 4);
define('COMMENT_PUSH', 5);
define('REVIEW_PUSH', 6);
define('CHAT_PUSH', 7);
/*
 * Encrypt Key
 */
defined("OPEN_SSL_KEY") OR define('OPEN_SSL_KEY', '011b519a043dcb915314695e1ce560dd4e29dae06867cdb701ffc96350e18caf');

defined("PRIVATE_USER") OR define("PRIVATE_USER", 1);
defined("INSTALLER") OR define("INSTALLER", 2);
defined("ARCHITECT") OR define("ARCHITECT", 3);
defined("ELECTRICAL_PLANNER") OR define("ELECTRICAL_PLANNER", 4);
defined("WHOLESALER") OR define("WHOLESALER", 5);
defined("BUSINESS_USER") OR define("BUSINESS_USER", 6);

/* ROLES */
defined("ROLE_USER") or define("ROLE_USER", 0);
defined("ROLE_EMPLOYEE") or define("ROLE_EMPLOYEE", 1);
defined("ROLE_OWNER") or define("ROLE_OWNER", 2);

/* Application Types */
defined("APPLICATION_RESIDENTIAL") or define("APPLICATION_RESIDENTIAL", 1);
defined("APPLICATION_PROFESSIONAL") or define("APPLICATION_PROFESSIONAL", 2);