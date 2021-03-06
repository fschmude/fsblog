<?
/**
 * SQLs for table artikel
 */
require_once PATH_PRIVATE.'D/DB.php';

class DArtikel extends DB {
  
  public function __construct() {
    parent::__construct('artikel', array(
      'titel' => 'string',
      'url' => 'string',
      'metadesc' => 'string',
      'datum' => 'date',
      'text' => 'string',
      'status' => 'int'   
    ));
  }
  
  
  /**
   * Die neuesten [anz] aktiven Artikel holen
   * @param int $anz
   */
  public function getTop($anz, $bMitText) {
    $sql = "SELECT id aid,datum,titel,url";
    if ($bMitText) {
      $sql .= ",text";
    }
    $sql .= " FROM artikel"
      ." WHERE status=1"
      ." ORDER BY id DESC"
      ." LIMIT ".$anz
    ;
    $stmt = $this->getPdo()->prepare($sql);
    if (!$stmt->execute()) {
      throw new Exception('Fehler beim Suchen der '.$anz.' neuesten Artikel');
    }
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $res;
  }
  

  /**
   * Einen Artikel anhand der fake-url holen
   */
  public function getArtikelByUrl($url) {
    $stmt = $this->getPdo()->prepare("SELECT * FROM artikel WHERE url=:url");
    $stmt->bindParam(':url', $url);
    if (!$stmt->execute()) {
      throw new Exception('Fehler beim Holen des Artikels mit url='.$url);
    }
    $art = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$art) {
      throw new Exception('Es gibt keinen Artikel mit url='.$url);
    }

    return $art;
  }
  
  
  /**
   * Liste für alle.php
   */
  public function getAllLive() {
    $stmt = $this->getPdo()->prepare(
      "SELECT id aid,datum,titel,url,text"
      ." FROM artikel"
      ." WHERE status=1"
      ." ORDER BY id DESC"
    );
    if (!$stmt->execute()) {
      throw new Exception('Fehler beim Ausliefern aller Artikel');
    }
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $res;
  }
  
  
  /**
   * Liste für Übersicht im Backend
   */
  public function getList() {
    $sql = "SELECT id,titel,datum,status,url"
      ." FROM artikel"
      ." ORDER BY id DESC"
    ;
    $stmt = $this->getPdo()->prepare($sql);
    $stmt->bindParam(':anz', $anz);
    if (!$stmt->execute()) {
      throw new Exception('Fehler bei '.$sql);
    }
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $res;
  }
  
  
}

