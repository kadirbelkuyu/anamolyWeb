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
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

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
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

defined('USER_ADMIN')         OR define('USER_ADMIN','1');
defined('USER_PLANNER')           OR define('USER_PLANNER','2');
defined('USER_BUYER')           OR define('USER_BUYER','3');
defined('USER_CUSTOMER')           OR define('USER_CUSTOMER','4');
defined('USER_COMPANY')           OR define('USER_COMPANY','5');
defined('USER_SUBADMIN')           OR define('USER_SUBADMIN','6');

defined('IMAGES_PATH')     OR define('IMAGES_PATH','./uploads/images');
defined('PROFILE_IMAGE_PATH')     OR define('PROFILE_IMAGE_PATH','./uploads/profile');
defined('ADMIN_THEME_BASE')    OR define('ADMIN_THEME_BASE','themes/backend');
defined('BASE_TEMPLATE')       OR define('BASE_TEMPLATE','admin/base_template/base_container');
defined('CATEGORY_IMAGE_PATH') OR define('CATEGORY_IMAGE_PATH','./uploads/categories');
defined('INGREDIENT_IMAGE_PATH') OR define('INGREDIENT_IMAGE_PATH','./uploads/ingredients');
defined('BANNER_IMAGE_PATH') OR define('BANNER_IMAGE_PATH','./uploads/banners');
defined('PRODUCT_IMAGE_PATH') OR define('PRODUCT_IMAGE_PATH','./uploads/products');
defined('SUGGESTION_IMAGE_PATH')     OR define('SUGGESTION_IMAGE_PATH','./uploads/suggestion');
defined('APP_IMAGE_PATH')     OR define('APP_IMAGE_PATH','./uploads/app');

defined('MYSQL_DATE_TIME_FORMATE') OR define('MYSQL_DATE_TIME_FORMATE','Y-m-d h:i:s');
defined('MYSQL_TIME_FORMATE') OR define('MYSQL_TIME_FORMATE','H:i:s');  // time formate
defined('MYSQL_DATE_FORMATE') OR define('MYSQL_DATE_FORMATE','Y-m-d');  // date formate
defined('DEFAULT_DATE_FORMATE') OR define('DEFAULT_DATE_FORMATE','d-m-Y');  // common date
//defined('DEFAULT_TIME_FORMATE') OR define('DEFAULT_TIME_FORMATE','h:i A');  // time formate
//defined('DEFAULT_DATE_TIME_FORMATE') OR define('DEFAULT_DATE_TIME_FORMATE','d-m-Y h:i A');  // common date and time
defined('DEFAULT_TIME_FORMATE') OR define('DEFAULT_TIME_FORMATE','H:i');  // time formate
defined('DEFAULT_DATE_TIME_FORMATE') OR define('DEFAULT_DATE_TIME_FORMATE','d-m-Y H:i');  // common date and time
defined('DEFAULT_DATE_PICKER_FORMATE') OR define('DEFAULT_DATE_PICKER_FORMATE','dd-mm-yyyy');

defined('APP_NAME') OR define('APP_NAME','Anamoly');

defined('RESPONCE') OR define('RESPONCE','responce');
defined('MESSAGE') OR define('MESSAGE','message');
defined('DATA') OR define('DATA','data');
defined('CODE') OR define('CODE','code');
defined('X_APP_VERSION')      OR define('X_APP_VERSION', 'X-App-Version');
defined('X_APP_LANGUAGE')      OR define('X_APP_LANGUAGE', 'X-App-Language');
defined('X_APP_DEVICE')      OR define('X_APP_DEVICE', 'X-App-Device');
defined('CODE_SUCCESS') OR define('CODE_SUCCESS',200);
defined('CODE_MISSING_INPUT') OR define('CODE_MISSING_INPUT',101);
defined('CODE_INVALID_INPUT') OR define('CODE_INVALID_INPUT',102);
defined('CODE_ACCESS_DENIDE') OR define('CODE_ACCESS_DENIDE',103);
defined('CODE_USER_NOT_FOUND') OR define('CODE_USER_NOT_FOUND',104);

defined('ORDER_PENDING') OR define('ORDER_PENDING',0);
defined('ORDER_CONFIRMED') OR define('ORDER_CONFIRMED',1);
defined('ORDER_OUT_OF_DELIVEY') OR define('ORDER_OUT_OF_DELIVEY',2);
defined('ORDER_DELIVERED') OR define('ORDER_DELIVERED',3);
defined('ORDER_DECLINE') OR define('ORDER_DECLINE',4);
defined('ORDER_CANCEL') OR define('ORDER_CANCEL',5);
defined('ORDER_UNPAID') OR define('ORDER_UNPAID',6);
defined('ORDER_PAID') OR define('ORDER_PAID',7);

defined('NOTIFICATION_TYPE_ORDER') OR define('NOTIFICATION_TYPE_ORDER','ORDER');
defined('NOTIFICATION_TYPE_OFFER') OR define('NOTIFICATION_TYPE_OFFER','OFFER');
defined('NOTIFICATION_TYPE_DISCOUNT') OR define('NOTIFICATION_TYPE_DISCOUNT','DISCOUNT');
defined('NOTIFICATION_TYPE_PRODUCT') OR define('NOTIFICATION_TYPE_PRODUCT','PRODUCT');
defined('NOTIFICATION_TYPE_ORDER_TRACK') OR define('NOTIFICATION_TYPE_ORDER_TRACK','ORDER OUT OF DELIVERY');
