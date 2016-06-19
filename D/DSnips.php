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
    
    // get start and end
    $jahr = substr($month, 0, 4);
    $monat = substr($month, 4);
    $folgeMonat = str_pad($monat + 1, 2, '0', STR_PAD_LEFT);
    
    $sql = "SELECT * FROM snips"
      ." WHERE datum>='".date('Y-m-d', strtotime($jahr.'-'.$monat.'-01'))."'"
      ." AND datum<'".date('Y-m-d', strtotime($jahr.'-'.$folgeMonat.'-01'))."'"
      ." ORDER BY id DESC"
    ;
    $q = $this->getPdo()->prepare($sql);
    if (!$q->execute()) {
      throw new Exception('Fehler bei '.$sql);
    }
    $result = $q->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  }
  
  
  /**
   * ID des vorigen Schnippels
   */
  public function getBefore($sid) {
    if (!$sid = (int) $sid) {
      throw new Exception('Keine gültige sid angegeben');
    }
    $sql = "SELECT max(id) bid FROM snips WHERE id<:sid";
    $q = $this->getPdo()->prepare($sql);
    if (!$q->execute(array(':sid' => $sid))) {
      throw new Exception('Fehler bei '.$sql);
    }
    $res = $q->fetch(PDO::FETCH_ASSOC);
    return $res['bid'];
  }
  
  
  /**
   * ID des nachfolgenden Schnippels
   */
  public function getAfter($sid) {
    if (!$sid = (int) $sid) {
      throw new Exception('Keine gültige sid angegeben');
    }
    $sql = "SELECT min(id) nid FROM snips WHERE id>:sid";
    $q = $this->getPdo()->prepare($sql);
    if (!$q->execute(array(':sid' => $sid))) {
      throw new Exception('Fehler bei '.$sql);
    }
    $res = $q->fetch(PDO::FETCH_ASSOC);
    return $res['nid'];
  }
  
  
  /**
   * Ein Monat
   * @param string $monat = '2016-05'
   */
  public function getMonat($monat) {
    $sql = "SELECT id, date_format(datum, '%Y-%m-%d %H:%i') datum, text, fbid FROM snips"
      ." WHERE datum>=:von"
      ." AND datum<:bis"
      ." ORDER BY id"
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

