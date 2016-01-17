<?
require_once 'D/DArtikel.php';
require_once 'D/DLeser.php';
require_once 'M/Model.php';
require_once 'M/MEmail.php';
require_once 'M/MHelper.php';

class MLeser extends Model {
  
  private $dobj = null;
  
  /**
   * Konstruktor
   */
  public function __construct() {
    $this->dobj = new DLeser;
  }
  

  /**
   * Eine Leseradresse eintragen
   */
  public function createLeser($lmail) {
    if (!$lmail = trim($lmail)) {
      throw new Exception('Keine E-Mail-Adresse angegeben.');
    }
    $Helper = new MHelper();
    $code = $Helper->makeCode();
    $this->dobj->createValues(array(
      'lmail' => $lmail,
      'code' => $code,
      'datum' => date('Y-m-d H:i:s'),
      'status' => 0
    ));
    
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
    $e = new MEmail();
    $e->mailen($lmail, 'fs-blog.de: Adresse bestätigen', $mtext);
  }
  
  /**
   * Leseradresse bestätigen
   */
  public function confirm($code) {
    // code suchen
    $leser = $this->dobj->getRowByCode($code);

    switch ($leser['status']) {
    case 0:
      // freischalten
      $this->dobj->setField($leser['id'], 'status', 1);
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
  public function getMaildata($aid) {
    if (!$aid) {
      throw new Exception('Keine aid angegeben');
    }
    
    // go
    $dart = new DArtikel;
    $art = $dart->getRow($aid);
    
    // add leser
    $leser = $this->dobj->getReaders();
    $art['leser'] = implode(',', $leser);

    return $art;
  }
    
}

