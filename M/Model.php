<?
require_once 'config.php';

class Model {
  
  /**
   * Get the DB connection handle
   */
  public function get_pdo() {
    if (!isset($GLOBALS['pdo']) || !$GLOBALS['pdo']) {
      $GLOBALS['pdo'] = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
    }
    return $GLOBALS['pdo'];
  }
   
}

