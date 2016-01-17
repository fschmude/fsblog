<?
require_once 'D/DBilder.php';
require_once 'D/DArtikel.php';
require_once 'D/DPosts.php';
require_once 'M/Model.php';
require_once 'M/MEmail.php';
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
  
}

