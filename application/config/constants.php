<?php


defined('BASEPATH') or exit('No direct script access allowed');

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
defined('SHOW_DEBUG_BACKTRACE') or define('SHOW_DEBUG_BACKTRACE', true);

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
defined('FILE_READ_MODE') or define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') or define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE') or define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE') or define('DIR_WRITE_MODE', 0755);

/*
  |--------------------------------------------------------------------------
  | File Stream Modes
  |--------------------------------------------------------------------------
  |
  | These modes are used when working with fopen()/popen()
  |
 */
defined('FOPEN_READ') or define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE') or define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE') or define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE') or define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE') or define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE') or define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT') or define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT') or define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

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

defined('BASE_URL') or define('BASE_URL', $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST']).'/sguide'; // no errors
defined('EXIT_SUCCESS') or define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR') or define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG') or define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE') or define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS') or define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') or define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT') or define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE') or define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN') or define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX') or define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

/* HEADER STATUS CONSTANTS */
defined("UNAUTHORIZED_ACCESS") or define("UNAUTHORIZED_ACCESS", 401);
defined("NOT_AUTHENTICATED") or define("NOT_AUTHENTICATED", 403);
defined("ACCESS_TOKEN_NOT_SET") or define("ACCESS_TOKEN_NOT_SET", 406);

/* FIELD DOESNT MATCH */
defined("OLD_PASSWORD_MISMATCH") or define("OLD_PASSWORD_MISMATCH", 490);
defined("PASSWORD_MISMATCH") or define("PASSWORD_MISMATCH", 491);
defined("NEW_PASSWORD_SAME") or define("NEW_PASSWORD_SAME", 492);

/* User status */
defined('ACTIVE') or define('ACTIVE', 1);
defined('BLOCKED') or define('BLOCKED', 2);
defined('DELETED') or define('DELETED', 3);
defined('INACTIVE') or define('INACTIVE', 0);
defined('DEFAULT_DB_DATE_TIME_FORMAT') or define('DEFAULT_DB_DATE_TIME_FORMAT', date("Y-m-d H:i:s"));
defined('COOKIE_EXPIRY_TIME') or define("COOKIE_EXPIRY_TIME", 86400 * 7);

// device type

defined('ANDROID') or define('ANDROID', 1);
defined('IPHONE') or define('IPHONE', 2);

//update type
defined('NORMAL') or define('NORMAL', 1);
defined('SKIPPABLE') or define('SKIPPABLE', 2);
defined('FORCEFULLY') or define('FORCEFULLY', 3);

defined('YES') or define('YES', 1);
defined('NO') or define('NO', 0);

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
defined("AWS_ACCESSKEY") or define('AWS_ACCESSKEY', 'AKIAI5YANLJPWEKLH3PQ');
defined('AWS_SECRET_KEY') or define('AWS_SECRET_KEY', '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');

/* image constants */
defined("AMAZONS3_BUCKET") or define("AMAZONS3_BUCKET", "appinventiv-development");

/* Content Type */
defined("CONTENT_TYPE_IMAGE") or define("CONTENT_TYPE_IMAGE", 1);
defined("CONTENT_TYPE_VIDEO") or define("CONTENT_TYPE_VIDEO", 2);
defined("CONTENT_TYPE_PDF") or define("CONTENT_TYPE_PDF", 3);

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
defined("OPEN_SSL_KEY") or define('OPEN_SSL_KEY', '011b519a043dcb915314695e1ce560dd4e29dae06867cdb701ffc96350e18caf');

defined("PRIVATE_USER") or define("PRIVATE_USER", 1);
defined("BUSINESS_USER") or define("BUSINESS_USER", 6);
defined("INSTALLER") or define("INSTALLER", 2);
defined("ARCHITECT") or define("ARCHITECT", 3);
defined("ELECTRICAL_PLANNER") or define("ELECTRICAL_PLANNER", 4);
defined("WHOLESALER") or define("WHOLESALER", 5);

/* ROLES */
defined("ROLE_USER") or define("ROLE_USER", 0);
defined("ROLE_EMPLOYEE") or define("ROLE_EMPLOYEE", 1);
defined("ROLE_OWNER") or define("ROLE_OWNER", 2);

/* Application Types */
defined("APPLICATION_RESIDENTIAL") or define("APPLICATION_RESIDENTIAL", 1);
defined("APPLICATION_PROFESSIONAL") or define("APPLICATION_PROFESSIONAL", 2);

define('ABS_PATH', getcwd().'/public/images/');

defined("EMPLOYEE_REQUEST_PENDING") or define("EMPLOYEE_REQUEST_PENDING", 0);
defined("EMPLOYEE_REQUEST_ACCEPTED") or define("EMPLOYEE_REQUEST_ACCEPTED", 1);
defined("EMPLOYEE_REQUEST_REJECTED") or define("EMPLOYEE_REQUEST_REJECTED", 3);
defined("EMPLOYEE_REQUEST_CANCELLED") or define("EMPLOYEE_REQUEST_CANCELLED", 4);
defined("RECORDS_PER_PAGE") or define("RECORDS_PER_PAGE", 10);
defined("API_RECORDS_PER_PAGE") or define("API_RECORDS_PER_PAGE", 10);

/*
| Mounting Type constants
|
*/
defined("MOUNTING_SUSPENDED") or define("MOUNTING_SUSPENDED", 1);
defined("MOUNTING_RECESSED") or define("MOUNTING_RECESSED", 2);
defined("MOUNTING_SURFACE") or define("MOUNTING_SURFACE", 3);
defined("MOUNTING_DOWNLIGHT") or define("MOUNTING_DOWNLIGHT", 4);
defined("MOUNTING_DOWNLIGHT_ISOSAFE") or define("MOUNTING_DOWNLIGHT_ISOSAFE", 5);
defined("MOUNTING_PENDANT") or define("MOUNTING_PENDANT", 6);
defined("MOUNTING_TRACKS") or define("MOUNTING_TRACKS", 7);

defined("NO") or define("NO", 0);
defined("YES") or define("YES", 1);
defined("ROOM_MAIN_PRODUCT") or define("ROOM_MAIN_PRODUCT", 1);
defined("ROOM_ACCESSORY") or define("ROOM_ACCESSORY", 2);
defined("REQUEST_SEARCH_RADIUS") or define("REQUEST_SEARCH_RADIUS", 20);
defined("AWAITING_REQUEST") or define("AWAITING_REQUEST", 1);
defined("QUOTED_REQUEST") or define("QUOTED_REQUEST", 2);
defined("APPROVED_REQUEST") or define("APPROVED_REQUEST", 3);

defined("QUOTATION_STATUS_QUOTED") or define("QUOTATION_STATUS_QUOTED", 1);
defined("QUOTATION_STATUS_APPROVED") or define("QUOTATION_STATUS_APPROVED", 2);
defined("QUOTATION_STATUS_REJECTED") or define("QUOTATION_STATUS_REJECTED", 3);

defined("CUSTOMER_QUOTATION_APPROVE") or define('CUSTOMER_QUOTATION_APPROVE', 1);
defined("CUSTOMER_QUOTATION_REJECT") or define('CUSTOMER_QUOTATION_REJECT', 2);

