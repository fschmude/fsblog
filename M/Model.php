<?
require_once 'config.php';

class Model {
  
  protected $pdo = null;
  
  /**
   * Getter for testing
   */
  public function get_pdo() {
    return $this->pdo;
  }
   
  /**
   * Constructor
   */
  public function __construct() {
    $this->pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
  }
  
}
