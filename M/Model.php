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
    switch (ORT) {
    case("live"):
      //$server = "rdbms.strato.de"; $db = "DB432485"; $user = "U432485"; $pw = "2GProfil";  
      $this->pdo = new PDO('mysql:host=rdbms.strato.de;dbname=DB1263851', 'U1263851', '2GProfil');
      break;
    case("lokal"):
      $this->pdo = new PDO('mysql:host=localhost;dbname=fsblog', 'root', 'Devel4Op');
      break;
    case("yc"):
      $this->pdo = new PDO('mysql:host=localhost;dbname=fsblog', 'root', 'MeineKleineDB');
      break;
    default:
      throw new Exception('Model.php:19:Falsches ORT: "'.ORT.'"' );
      break;
    }
  }
  
}
