<?
/**
 * SQLs for table leser
 */
require_once 'D/DB.php';

class DLeser extends DB {
  
  public function __construct() {
    parent::__construct('leser', array(
      'lmail' => 'string',
      'datum' => 'date',
      'code' => 'string',
      'status' => 'int'
    ));
  }
  
  
  /**
   * select a row by its confirmation code
   */
  public function getRowByCode($code) {
    if (!$code = trim($code)) {
      throw new Exception('Kein code angegeben');
    }
    $stmt = $this->getPdo()->prepare("SELECT id, lmail, code, status FROM leser WHERE code=:code");
    $stmt->bindParam(':code', $code);
    if (!$stmt->execute()) {
      throw new Exception('Fehler beim Suchen nach code="'.$code.'"');
    }
    $leser = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!isset($leser['lmail'])) {
      throw new Exception('Es existiert kein Eintrag mit code="'.$code.'"');
    }
    return $leser;
  }
  
  
  /**
   * Get all confirmed readers
   */
  public function getReaders() {
    $stmt = $this->getPdo()->prepare("SELECT lmail FROM leser WHERE status=1");
    if (!$stmt->execute()) {
      throw new Exception('Fehler beim Suchen der Leser');
    }
    $leser = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $leser[] = $row['lmail'];
    }
    return $leser;
  }

}

