<?
require_once 'D/DBilder.php';
require_once 'D/DArtikel.php';
require_once 'D/DPosts.php';
require_once 'M/Model.php';
require_once 'M/Email.php';
require_once 'M/MHelper.php';

define('TEASER_LENGTH', 300);

class MArtikel extends Model {
  
  private $dobj = null;
  
  /**
   * Konstruktor
   */
  public function __construct() {
    $this->dobj = new DArtikel;
  }
  
  /**
   * Alle aktiven(!) Artikel holen, mit Text
   */
  public function getAllLive() {
    $res = $this->dobj->getAllLive();
    
    // wortgenaues Abschneiden
    $this->snipText($res);
    
    return $res;
  }
  
  /**
   * @param byref array $rows = numeric array of rows, 
   *    $row = array( 'text' => '...', ...);
   */
  private function snipText(&$rows) {
    foreach ($rows as &$row) {
      if (strlen($row['text']) > TEASER_LENGTH) {
        if (!$pos = strpos($row['text'], ' ', TEASER_LENGTH - 20)) {
          $pos = TEASER_LENGTH;
        }
        $row['text'] = substr($row['text'], 0, $pos);
      }
    }
  }
  
  /**
   * Übersichtsliste für admin
   */
  public function getList() {
    $res = $this->dobj->getList();
    return $res;
  }
  
  /**
   * Genau einen Artikel holen, mit Text
   * @param int $aid
   */
  public function getItem($aid) {
    if (!$aid = (int)$aid) {
      throw new Exception('Keine gültige aid übergeben');
    }
    $res = $this->dobj->getRow($aid);
    return $res;
  }
  
  /**
   * Artikel updaten
   * @param array()
   */
  public function edit($art) {
    if (!isset($art['id']) || !(int)$art['id']) {
      throw new Exception('Ungültige ID beim Editieren eines Artikels');
    }
    
    $this->dobj->edit($art);
    return true;
  }
  
  /**
   * Die neuesten [anz] Artikel holen, mit oder ohne Teaser
   * @param int $anz
   * @param bool $bMitTeaser (default = false)
   */
  public function getTop($anz, $bMitTeaser = false) {
    if (!$anz = (int) $anz) {
      throw new Exception('anz ('.$anz.') ist kein positives Int');
    }
    
    $res = $this->dobj->getTop($anz, $bMitTeaser);
    
    // Falls mit Text, diesen wortgenau Abschneiden
    if ($bMitTeaser) {
      $this->snipText($res);
    }
    
    return $res;
  }
  
  /**
   * Einen Artikel zusammen mit Postings und Bildern holen - anhand der fake-url
   * Bei nicht freigeschalteten Artikeln Anmeldung erforderlich!
   */
  public function getArtikelKomplettByUrl($url) {
    $art = $this->dobj->getArtikelByUrl($url);
    
    // Freigeschaltet?
    $this->checkPublic($art);

    $result = $this->addDependentRows($art);
    return $result;
  }
  
  /**
   * Einen Artikel zusammen mit Postings und Bildern holen - anhand der ID
   * Bei nicht freigeschalteten Artikeln Anmeldung erforderlich!
   */
  public function getArtikelKomplett($aid) {
    $art = $this->dobj->getRow($aid);
    
    // Freigeschaltet?
    $this->checkPublic($art);

    $result = $this->addDependentRows($art);
    return $result;
  }
  
  /**
   * Check, ob ein Artikel angezeigt werden darf
   * @param array $art = ds aus artikel
   * @return true, falls ok
   * @throws Exception, falls nicht freigeschaltet und keine Backend-Anmeldung
   */
  private function checkPublic($art) {
    if (!(int)$art['status'] && !(isset($_SESSION['ok']) && $_SESSION['ok'])) {
      throw new Exception('Dieser Artikel ist nicht freigeschaltet.');
    }
    return true;
  }
  
  /**
   * Postings und Bilder-Infos zu einem Artikel-DS hinzufügen
   * @param array row aus artikel
   * @return array(
   *    'id' =>
   *    'titel' =>
   *    'metadesc' =>
   *    'datum' =>
   *    'text' =>
   *    'posts' => array()
   *    'bilder' => array()
   * );
   */
  private function addDependentRows($art) {
    // hat's Bilder?
    $dbilder = new DBilder;
    $art['bilder'] = array();
    $pos = 0;
    $search = '<imga id="';
    while ($pos = strpos($art['text'], $search, $pos)) {
      $pos_e = strpos($art['text'], '>', $pos + 10);
      $bid = substr($art['text'], $pos + 10, $pos_e - $pos - 11);
      //$st_b->bindParam(':bid', $bid);
      $bild = $dbilder->getRow($bid);
      if (!isset($bild['ext']) || !strlen($bild['ext'])) {
        // Fehlerbild ausliefern
        $bild = array(
          'id' => $bid,
          'width' => '100',
          'height' => '50',
          'url' => 'fehler',
          'alt' => 'Kein Bild-Datensatz!',
          'ext' => 'gif'
        );
      }
      $art['bilder'][] = $bild;
      $pos++;
    }
    
    // Postings
    $dposts = new DPosts;
    $posts = $dposts->getPostsForAid($art['id']);
    $art['posts'] = $posts;
    
    return $art;
  }
  
  /**
   * posting erzeugen, noch nicht freigeschaltet!
   */
  public function createPost($aid, $username, $usermail, $ptext) {
    $dposts = new DPosts;
    // lfnr berechnen
    $oldLfnr = $dposts->getMaxLfnr($aid);
    $lfnr = (int) $oldLfnr + 1;
    
    // speichern
    $Helper = new MHelper();
    $code = $Helper->makeCode();
    $pid = $dposts->createValues(array(
      'aid' => $aid,
      'lfnr' => $lfnr,
      'code' => $code,
      'username' => $username,
      'usermail' => $usermail,
      'text' => $ptext,
      'datum' => date('Y-m-d H:i:s'),
      'status' => 0
    ));
    
    // mailen
    $mtext = 'Liebe/r '.$username.','."\n\n"
      .'Ihre E-Mail-Adresse ('.$usermail.') wurde auf fs-blog.de als Urheber des folgenden Kommentars angegeben:'."\n\n"
      .'"'.$ptext.'"'."\n\n"
      .'Wenn Sie wirklich wollen, dass dieser Beitrag veröffentlicht wird, klicken Sie bitte hier:'."\n"
      .BASEURL.'confirm.php?pid='.$pid.'&code='.$code."\n\n"
      .'(Wenn Ihr E-Mail-Programm diesen Link nicht klickbar anzeigt, kopieren Sie ihn bitte in das URL-Fenster eines Browsers.)'."\n\n"
      .'Wenn der Beitrag nicht von Ihnen stammt, so ignorieren Sie diese Mail bitte.'."\n\n"
      .'Mit freundlichen Grüßen'."\n\n"
      .'fs'."\n\n"
    ;
    
    $e = new Email();
    $e->mailen($usermail, 'Ihr Beitrag auf fs-blog.de', $mtext);
    return $pid;
  }
  
  
  /**
   * Artikel-URL zu einer aid bestimmen
   * @param int = article ID
   * @return string
   */
  public function getUrl($aid) {
    return $this->dobj->getUrl($aid);
  }
  
  
  /**
   * Neuen Artikel anlegen
   * @return int = article ID
   */
  public function create() {
    $aid = $this->dobj->createValues(array(
      'datum' => date('Y-m-d H:i:s'),
      'status' => 0,
      'titel' => '',
      'text' => '',
      'metadesc' => ''
    ));
    return $aid;
  }
  
  
  /**
   * Artikel löschen
   */
  public function delete($aid) {
    $this->dobj->delete($aid);
  }
  
  
  /**
   * posting bestätigen
   */
  public function confirmPost($pid, $code) {
    $dposts = new DPosts;
    $post = $dposts->getRow($pid);
    
    // check code
    if ($post['code'] != $code) {
      throw new Exception('Code ('.$code.') stimmt nicht');
    }

    switch ($post['status']) {
    case 0:
      // ok, jetzt status setzen
      $dposts->setStatus($pid, 1);
      
      // an mich mailen
      $mtext = 'Bestätigung der E-Mail-Adresse von '.$post['username'].': '.$post['usermail']."\n\n"
        .'"'.$post['text'].'"'."\n\n"
        .'Beitrag freischalten:'."\n\n"
        .BASEURL.'admin.php'."\n\n"
      ;
      $e = new Email();
      $e->mailen(EMAIL_ADMIN, 'fs-blog.de: Freischaltung', $mtext);
      break;
      
    case 1:
      // schon freigeschaltet, vermutlich doppelt geklickt
      break;
      
    default:
      // verboten, gelöscht oder sonstwas
      throw new Exception('Dieser Beitrag darf nicht freigeschaltet werden.');
    }
    
    // return the whole posting row
    return $post;
  }
  
}

