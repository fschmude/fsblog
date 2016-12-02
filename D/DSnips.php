<?
/**
 * SQLs for table snips (Snippets, Schnippel)
 */
require_once 'D/DB.php';

class DSnips extends DB {
  
  public function __construct() {
    parent::__construct('snips', array(
      'text' => 'string',
      'datum' => 'date',
      'fbid' => 'string'
    ));
  }
  

  /**
   * @param string $month
   */
  public function getList($month) {
    if (!$month = trim($month)) {
      throw new Exception('Kein Monat angegeben');
    }
    if (strlen($month) != 6) {
      throw new Exception('month ('.$month.') hat nicht Länge 6');
    }
    
    // get correct dates for first of month, first of next month
    $jahr = substr($month, 0, 4);
    $monat = substr($month, 4);
    $dtM = new DateTime($jahr.'-'.$monat.'-01');
    $dat1 = $dtM->format('Y-m-d');
    $dtM->add(new DateInterval('P1M'));
    $datNext1 = $dtM->format('Y-m-d');
    
    // go
    $sql = "SELECT * FROM snips"
      ." WHERE datum>='".$dat1."'"
      ." AND datum<'".$datNext1."'"
      ." ORDER BY datum DESC"
    ;
    $q = $this->getPdo()->prepare($sql);
    if (!$q->execute()) {
      throw new Exception('Fehler bei '.$sql);
    }
    $result = $q->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  }
  
  
  /**
   * ID des datumsmäßig vorigen Schnippels
   */
  public function getBefore($sid) {
    if (!$sid = (int) $sid) {
      throw new Exception('Keine gültige sid angegeben');
    }
    $sql = "SELECT id FROM snips"
      ." WHERE datum < (SELECT datum FROM snips WHERE id=:sid)"
      ." ORDER BY datum DESC"
    ;
    $q = $this->getPdo()->prepare($sql);
    if (!$q->execute(array(':sid' => $sid))) {
      throw new Exception('Fehler bei '.$sql);
    }
    $res = $q->fetch(PDO::FETCH_ASSOC);
    return $res['id'];
  }
  
  
  /**
   * ID des datumsmäßig nachfolgenden Schnippels
   */
  public function getAfter($sid) {
    if (!$sid = (int) $sid) {
      throw new Exception('Keine gültige sid angegeben');
    }
    $sql = "SELECT id FROM snips"
      ." WHERE datum > (SELECT datum FROM snips WHERE id=:sid)"
      ." ORDER BY datum"
    ;
    $q = $this->getPdo()->prepare($sql);
    if (!$q->execute(array(':sid' => $sid))) {
      throw new Exception('Fehler bei '.$sql);
    }
    $res = $q->fetch(PDO::FETCH_ASSOC);
    return $res['id'];
  }
  
  
  /**
   * Ein Monat, nach Datum geordnet
   * @param string $monat = '2016-05'
   */
  public function getMonat($monat) {
    $sql = "SELECT id, date_format(datum, '%Y-%m-%d %H:%i') datum, text, fbid FROM snips"
      ." WHERE datum>=:von"
      ." AND datum<:bis"
      ." ORDER BY datum DESC"
    ;
    $von = $monat.'-01';
    $dtVon = new DateTime($von);
    $dtBis = $dtVon->add(new DateInterval('P1M'));
    $bis = $dtBis->format('Y-m-d');
    $q = $this->getPdo()->prepare($sql);
    if (!$q->execute(array(':von' => $von, ':bis' => $bis))) {
      throw new Exception('Fehler bei '.$sql);
    }
    $res = $q->fetchAll(PDO::FETCH_ASSOC);
    return $res;
  }
  
  
  /**
   * Liste aller Monate (Ym), zu denen Schnippel existieren
   * @param int optional $anz = Anzahl neueste, falls 0 oder fehlt, dann alle 
   */
  public function getMonths($anz = 0) {
    $sql = "SELECT DISTINCT date_format(datum, '%Y%m') month FROM snips"
      ." ORDER BY month DESC"
    ;
    if ($anz = (int) $anz) {
      $sql .= " LIMIT ".$anz;
    }
    $q = $this->getPdo()->prepare($sql);
    if (!$q->execute()) {
      throw new Exception('Fehler bei '.$sql);
    }
    $result = $q->fetchAll(PDO::FETCH_ASSOC);
    $months = array();
    foreach ($result as $row) {
      $months[] = $row['month'];
    }
    return $months;
  }


}

