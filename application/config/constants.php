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
defined('FOPEN_READ_WRITE_CREATE_DESCTRUCTIVE') OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');
defined('REGISTRATION_PAGE')       				OR define('REGISTRATION_PAGE', 'registration');


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


defined('ASSETS_PATH')		   OR define('ASSETS_PATH', 'http://'.$_SERVER['HTTP_HOST'].'/shmyde/assets/'); // The assets folder of the application
defined('ASSETS_DIR_PATH')	   OR define('ASSETS_DIR_PATH', $_SERVER['DOCUMENT_ROOT'].'/shmyde/assets/'); // The assets folder of the application

defined('CURRENT_LANGUAGE')	   OR define('CURRENT_LANGUAGE', 'english'); // The current language of the application

defined('IMAGE_WIDTH')             OR define('IMAGE_WIDTH', 230); 
defined('IMAGE_HEIGHT')            OR define('IMAGE_HEIGHT', 300); 

// SHMYDE CONTACT
defined('SHMYDE_CONTACT')               OR define('SHMYDE_CONTACT', "contact@shmyde.com");
defined('SHMYDE_ADDRESS_LINE_1')        OR define('SHMYDE_ADDRESS_LINE_1', "Cameroon, YDE");
defined('SHMYDE_ADDRESS_LINE_2')        OR define('SHMYDE_ADDRESS_LINE_2', "00237, WestRoad Lane");
defined('SHMYDE_COUNTRY')               OR define('SHMYDE_COUNTRY', "Cameroon");
defined('SHMYDE_CONTACT_PHONE')         OR define('SHMYDE_CONTACT_PHONE', "00237 677777777");


// Table Names
defined('OPTION_TABLE')                 OR define('OPTION_TABLE', "shmyde_design_option"); 
defined('OPTION_IMAGE_TABLE')           OR define('OPTION_IMAGE_TABLE', "shmyde_images"); 
defined('OPTION_THUMBNAIL_TABLE')       OR define('OPTION_THUMBNAIL_TABLE', "shmyde_option_thumbnail"); 
defined('OPTION_DEPENDENT_MENU_TABLE')  OR define('OPTION_DEPENDENT_MENU_TABLE', "shmyde_option_dependent_menu"); 
defined('MENU_TABLE')                   OR define('MENU_TABLE', "shmyde_design_main_menu"); 
defined('PRODUCT_TABLE')                OR define('PRODUCT_TABLE', "shmyde_product"); 
defined('OPTION_BUTTON_TABLE')          OR define('OPTION_BUTTON_TABLE', "shmyde_style_buttons");
defined('THREADS_TABLE')                OR define('THREADS_TABLE', "shmyde_threads");
defined('BUTTONS_TABLE')                OR define('BUTTONS_TABLE', "shmyde_buttons");
defined('FABRICS_TABLE')                OR define('FABRICS_TABLE', "shmyde_fabrics");
defined('FABRIC_IMAGES_TABLE')          OR define('FABRIC_IMAGES_TABLE', "shmyde_fabric_images");
defined('PRODUCT_FABRC_MENU_TABLE')     OR define('PRODUCT_FABRC_MENU_TABLE', "shmyde_product_submenu_fabric");
defined('MEASUREMENTS_TABLE')           OR define('MEASUREMENTS_TABLE', "shmyde_measurement");
defined('USERS_TABLE')                  OR define('USERS_TABLE', "users");
defined('USER_DATA_TABLE')              OR define('USER_DATA_TABLE', "shmyde_user_data");
defined('USER_TMP_DESIGN_TABLE')        OR define('USER_TMP_DESIGN_TABLE', "shmyde_temp_user_design");
defined('ORDERS_TABLE')                 OR define('ORDERS_TABLE', "shmyde_orders");



