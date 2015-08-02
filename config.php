<?
/**
 * Hier dürfen sich nur globale Konstanten aufhalten!
 */
define('VERSION', '2.1');

define('BASEURL', 'http://www.fs-blog.de/'); 

define('BACKEND_PASSWORD', 'change_this');

define('DO_SEND_MAILS', true);  // true || false

// MySQL connection
define('DB_HOST', 'change_this');
define('DB_NAME', 'change_this');
define('DB_USER', 'change_this');
define('DB_PASS', 'change_this');

// errorhandling
error_reporting(E_ALL);
define('DISPLAY_ERRORS', true);  // true || false
ini_set('display_errors', DISPLAY_ERRORS);
define('LOG_FILE', __DIR__.'/log/error.log');

// Login fürs Backend
define('LOGIN_FILE', __DIR__.'/log/last_login.txt');
define('LOGIN_REFRAK', 10);


/*******************************************************************************
 Do not edit below this line
*******************************************************************************/

// configure global errorhandling
ini_set('log_errors', true);
ini_set('error_log', LOG_FILE);

function fatal_handler() {
  $err = error_get_last();
  if (is_array($err)) {
    $msg = 'Error (typ '.$err['type'].') in '.$err['file'].':'.$err['line'].': '.$err['message'];
    $f = fopen(LOG_FILE, 'a');
    fwrite($f, $msg."\n");
    fclose($f);
  }
}
register_shutdown_function('fatal_handler');

