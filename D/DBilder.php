<?
/**
 * SQLs for table bilder
 */
require_once 'D/DB.php';

class DBilder extends DB {
  
  public function __construct() {
    parent::__construct('bilder', array(
      'width' => 'int',
      'height' => 'int',
      'url' => 'string',
      'ext' => 'string',
      'alt' => 'string'
    ));
  }
  
  
  /**
   * Get row by url
   */
  public function getByUrl($url) {
    if (!strlen($url = trim($url))) {
      throw new Exception('Keine URL angegeben');
    }
    
    // go
    $stmt = $this->getPdo()->prepare("SELECT * FROM bilder WHERE url=:url");
    if (!$stmt->execute(array(':url' => $url))) {
      throw new Exception('Fehler beim Suchen nach url='.$url);
    }
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return $res;
  }


}

