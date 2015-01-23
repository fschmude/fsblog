<?
/**
 * Hier dürfen sich nur globale Konstanten aufhalten!
 */
define( 'ORT', 'lokal' ); // lokal | live
define( 'VERSION', '2.0' );

switch (ORT) {
case 'live':
  define('BASEURL', 'http://www.fs-blog.de/'); 
  define('DISPLAY_ERRORS', false);
  break;
case 'lokal':
  define('BASEURL', 'http://localhost/blog/');
  define('DISPLAY_ERRORS', true);
  // MySQL connection
  define('DB_HOST', 'localhost');
  define('DB_NAME', 'fsblog');
  define('DB_USER', 'root');
  define('DB_PASS', 'Devel4Op');
  break;
}

// errorhandling
error_reporting(E_ALL);
ini_set('display_errors', DISPLAY_ERRORS);
define('LOG_FILE', __DIR__.'/log/error.log');


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

