<?
require_once 'M/Model.php';
require_once 'M/Email.php';
require_once 'M/MHelper.php';

class MLeser extends Model {
  
  /**
   * Eine Leseradresse eintragen
   */
  public function create_leser($lmail) {
    $stmt = $this->get_pdo()->prepare(
      "INSERT INTO leser(lmail,code,datum,status) VALUES(:lmail,:code,SYSDATE(),0)"
    );
    $stmt->bindParam(':lmail', $lmail);
    $Helper = new MHelper();
    $code = $Helper->make_code();
    $stmt->bindParam(':code', $code);
    if (!$stmt->execute()) {
      throw new Exception('Fehler beim Einfügen der Mailadresse "'.$lmail.'"');
    }
    
    // Noch das Mail verschicken
    $mtext = 'Sehr geehrte/r Leser/in,'."\n\n"
      .'Ihre E-Mail-Adresse "'.$lmail.'" wurde auf fs-blog.de für die Benachrichtigung bei neuen Artikeln eingetragen.'."\n\n"
      .'Wenn Sie diese Eintragung wünschen, klicken Sie bitte auf diesen Link:'."\n"
      .BASEURL.'confirm.php?lmc='.$code."\n\n"
      .'Wenn Ihr E-Mail-Programm diesen Link nicht klickbar anzeigt, so kopieren Sie ihn bitte von Hand in ein Browser-Fenster.'."\n\n"
      .'Wenn die Eintragung nicht von Ihnen ist, so ignorieren Sie diese Mail bitte.'."\n\n"
      .'Viele Grüße'."\n\n"
      .'fs'
    ;
    $e = new Email();
    $e->mailen($lmail, 'fs-blog.de: Adresse bestätigen', $mtext);
  }
  
  /**
   * Leseradresse bestätigen
   */
  public function confirm($code) {
    // code suchen
    $stmt = $this->get_pdo()->prepare("SELECT id, lmail, code, status FROM leser WHERE code=:code");
    $stmt->bindParam(':code', $code);
    if (!$stmt->execute()) {
      throw new Exception('Fehler beim Suchen nach code="'.$code.'"');
    }
    $leser = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!isset($leser['lmail'])) {
      throw new Exception('Es existiert kein Eintrag mit code="'.$code.'"');
    }
    switch ($leser['status']) {
    case 0:
      // freischalten
      $stmt = $this->get_pdo()->prepare("UPDATE leser SET status=1 WHERE code=:code");
      $stmt->bindParam(':code', $code);
      if (!$stmt->execute()) {
        throw new Exception('Fehler beim Freischalten von code="'.$code.'"');
      }
      break;
      
    case 1:
      // schon freigeschaltet, Doppelklick?
      break;
      
    default:
      throw new Exception('Ungültiger Status: '.$leser['status']);
    }

    return $leser['lmail'];
  }

  
  /**
   * Leserliste und Hinweistext für Mailing erzeugen
   */
  public function getTeaser($aid) {
    if (!$aid) {
      throw new Exception('Keine aid angegeben');
    }
    
    // go
    $stmt = $this->get_pdo()->prepare("SELECT * FROM artikel WHERE id=:aid");
    if (!$stmt->execute(array(':aid' => $aid))) {
      throw new Exception('Fehler beim Suchen von Artikel mit aid='.$aid);
    }
    $art = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!isset($art['status'])) {
      throw new Exception('Es existiert kein Artikel mit aid='.$aid);
    }
    
    // add leser
    $stmt = $this->get_pdo()->prepare("SELECT lmail FROM leser WHERE status=1");
    if (!$stmt->execute()) {
      throw new Exception('Fehler beim Suchen der Leser');
    }
    $leser = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $leser[] = $row['lmail'];
    }
    $art['leser'] = implode(', ', $leser);
    
    return $art;
  }
  
  
}

