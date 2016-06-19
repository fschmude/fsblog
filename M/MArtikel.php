<?
require_once 'D/DBilder.php';
require_once 'D/DArtikel.php';
require_once 'D/DPosts.php';
require_once 'M/Model.php';
require_once 'M/MEmail.php';
require_once 'M/MHelper.php';
require_once 'M/MVideo.php';

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
    $this->completeAllUrls($res);
    
    // Alle Monate, mit Teaser
    $result = $this->addMonths($res, 0, true);
    
    return $result;
  }
  
  
  /**
   * Zu einer Artikelliste Monate hinzufügen
   */
  private function addMonths($aArtikel, $anz, $bMitTeaser) {
    $dSnips = $this->getObject('DSnips');
    $months = $dSnips->getMonths($anz);
    foreach ($months as $month) {
      $monthStrich = substr($month, 0, 4).'-'.substr($month, 4);
      $mrow = array(
        'datum' => $monthStrich.'-01',
        'titel' => 'Einträge von '.$monthStrich,
        'url' => $this->completeUrl(0, 0, $monthStrich)
      );
      if ($bMitTeaser) {
        $mrow['text'] = 'Hier ist die Sicherung aller Facebook-Einträge von '.$monthStrich.'. ';
      }
      $aArtikel[] = $mrow;
    }
    
    // Nach Datum sortieren
    $result = array();
    foreach ($aArtikel as $row) {
      $result[$row['datum']] = $row;
    }
    krsort($result);

    return $result;
  }
  
  
  /**
   * Übersichtsliste für admin
   */
  public function getList() {
    $res = $this->dobj->getList();
    $this->completeAllUrls($res);
    return array('rows' => $res);
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
    if ($url = trim($res['url'])) {
      $res['completeUrl'] = $this->completeUrl($res['url']);
    }
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
    
    // Artikel holen
    $res = $this->dobj->getTop($anz, $bMitTeaser);
    if ($bMitTeaser) {
      $this->snipText($res);
    }
    $this->completeAllUrls($res);
    
    // mit Monaten zusammensetzen, nur neueste
    $result = $this->addMonths($res, $anz, $bMitTeaser);
    $result = array_slice($result, 0, $anz);
    
    return $result;
  }


  /**
   * Helper: add dependent rows
   */
  private function addDependentRows($artikel) {
    // Freigeschaltet?
    $this->checkPublic($artikel);

    // add Posts
    $dposts = $this->getObject('DPosts');
    $posts = $dposts->getPostsForAid($artikel['id']);
    $artikel['posts'] = $posts;
    
    // add embedded objects
    $deps = $this->getEmbeddedRows($artikel['text']);
    $artikel['bilder'] = $deps['bilder'];
    $artikel['vids'] = $deps['vids'];
    
    $artikel['type'] = 'artikel';
    return $artikel;
  }
  
  
  /**
   * Einen Artikel zusammen mit Postings und Bildern holen - anhand der fake-url
   * Bei nicht freigeschalteten Artikeln Anmeldung erforderlich!
   */
  public function getArtikelKomplettByUrl($url) {
    $art = $this->dobj->getArtikelByUrl($url);
    $art = $this->addDependentRows($art);
    return $art;
  }
  
  /**
   * Einen Artikel zusammen mit Postings und Bildern holen - anhand der ID
   * Bei nicht freigeschalteten Artikeln Anmeldung erforderlich!
   */
  public function getArtikelKomplett($aid) {
    $art = $this->dobj->getRow($aid);
    $art = $this->addDependentRows($art);
    return $art;
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
   * Artikel-URL zu einer aid bestimmen
   * @param int = article ID
   * @return string
   */
  public function getUrl($aid) {
    return $this->dobj->getField($aid, 'url');
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
      'url' => '',
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
   * Die Spalte "text" in einem array auf globale Teaser-Länge verkürzen
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

}

