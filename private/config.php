<?
/**
 * Hier dürfen sich nur globale Konstanten aufhalten!
 */
define('BASEURL', 'http://localhost/fsblog/public/');
define('BASEPATH', __DIR__);

define('BACKEND_PASS_HASH', '7acf52c3d08d2b8650a27b7c0c134394');

define('DO_SEND_MAILS', false);  // true || false

// MySQL connection
define('DB_HOST', 'localhost');
define('DB_NAME', 'fsblog');
define('DB_USER', 'root');
define('DB_PASS', 'MeineDaten');

// errorhandling
error_reporting(E_ALL);
define('DISPLAY_ERRORS', true);  // true || false
ini_set('display_errors', DISPLAY_ERRORS);
define('LOG_FILE', __DIR__.'/log/error.log');

// Login fürs Backend
define('LOGIN_FILE', __DIR__.'/log/last_login.txt');
define('LOGIN_REFRAK', 5);


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
