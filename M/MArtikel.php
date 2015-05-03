<?
require_once 'M/Model.php';
require_once 'M/Email.php';
require_once 'M/MHelper.php';

define('TEASER_LENGTH', 300);

class MArtikel extends Model {
  
  /**
   * Alle Artikel holen, mit Text
   */
  public function get_all() {
    $stmt = $this->pdo->prepare(
      "SELECT id aid,datum,titel,url,text"
      ." FROM artikel"
      ." WHERE status=1"
      ." ORDER BY id DESC"
    );
    if (!$stmt->execute()) {
      throw new Exception('Fehler beim Suchen der '.$anz.' neuesten Artikel');
    }
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
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
    $sql = "SELECT id,datum,status,url"
      ." FROM artikel"
      ." ORDER BY id DESC"
    ;
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':anz', $anz);
    if (!$stmt->execute()) {
      throw new Exception('Fehler bei '.$sql);
    }
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    
    $stmt = $this->pdo->prepare(
      "SELECT *"
      ." FROM artikel"
      ." WHERE id=:aid"
    );
    if (!$stmt->execute(array(':aid' => $aid))) {
      throw new Exception('Fehler beim Suchen des Artikels mit aid='.$aid);
    }
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
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
    
    $stmt = $this->pdo->prepare(
      "UPDATE artikel SET titel=:titel, url=:url, metadesc=:metadesc, datum=:datum, text=:text, status=:status"
      ." WHERE id=:id"
    );
    $stmt->bindParam(':id', $art['id']);
    $stmt->bindParam(':titel', $art['titel']);
    $stmt->bindParam(':url', $art['url']);
    $stmt->bindParam(':metadesc', $art['metadesc']);
    $stmt->bindParam(':datum', $art['datum']);
    $stmt->bindParam(':text', $art['text']);
    $stmt->bindParam(':status', $art['status']);
    if (!$stmt->execute()) {
      throw new Exception('Fehler beim Editieren des Artikels mit aid='.$art['id']);
    }
    return true;
  }
  
  /**
   * Die neuesten [anz] Artikel holen, mit oder ohne Teaser
   * @param int $anz
   * @param bool $bMitTeaser (default = false)
   */
  public function get_top($anz, $bMitTeaser = false) {
    if (!$anz = (int) $anz) {
      throw new Exception('anz ('.$anz.') ist kein positives Int');
    }
    
    $sql = "SELECT id aid,datum,titel,url";
    if ($bMitTeaser) {
      $sql .= ",text";
    }
    $sql .=" FROM artikel"
      ." WHERE status=1"
      ." ORDER BY id DESC"
      ." LIMIT ".$anz
    ;
    $stmt = $this->pdo->prepare($sql);
    if (!$stmt->execute()) {
      throw new Exception('Fehler beim Suchen der '.$anz.' neuesten Artikel');
    }
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
  public function get_artikel_komplett_by_url($url) {
    $stmt = $this->pdo->prepare("SELECT * FROM artikel WHERE url=:url");
    $stmt->bindParam(':url', $url);
    if (!$stmt->execute()) {
      throw new Exception('Fehler beim Holen des Artikels mit url='.$url);
    }
    $art = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$art) {
      throw new Exception('Es gibt keinen Artikel mit url='.$url);
    }
    
    // Freigeschaltet?
    
    if (!(int)$art['status'] && !(isset($_SESSION['ok']) && $_SESSION['ok'])) {
      throw new Exception('Dieser Artikel ist nicht freigeschaltet.');
    }

    $result = $this->add_dependent_rows($art);
    return $result;
  }
  
  /**
   * Einen Artikel zusammen mit Postings und Bildern holen - anhand der ID
   */
  public function get_artikel_komplett($aid) {
    $stmt = $this->pdo->prepare("SELECT * FROM artikel WHERE id=:aid");
    $stmt->bindParam(':aid', $aid);
    if (!$stmt->execute()) {
      throw new Exception('Fehler beim Holen des Artikels mit id='.$aid);
    }
    $art = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$art) {
      throw new Exception('Es gibt keinen Artikel mit id='.$aid);
    }

    $result = $this->add_dependent_rows($art);
    return $result;
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
  private function add_dependent_rows($art) {
    // hat's Bilder?
    $art['bilder'] = array();
    $pos = 0;
    $search = '<imga id="';
    $st_b = $this->pdo->prepare( "SELECT * FROM bilder WHERE id=:bid" );
    while ($pos = strpos($art['text'], $search, $pos)) {
      $pos_e = strpos($art['text'], '>', $pos + 10);
      $bid = substr($art['text'], $pos + 10, $pos_e - $pos - 10);
      $st_b->bindParam(':bid', $bid);
      if (!$st_b->execute()) {
        throw new Exception('Fehler beim Holen von Bild Nr. '.$bid);
      }
      $bild = $st_b->fetch(PDO::FETCH_ASSOC);
      if (!isset($bild['ext']) || !strlen($bild['ext'])) {
        throw new Exception('Kein Eintrag für Bild Nr. '.$bid);
      }
      $art['bilder'][] = $bild;
      $pos++;
    }
    
    // Postings
    $art['posts'] = array();
    $st_p = $this->pdo->prepare(
      "SELECT * FROM posts"
      ." WHERE aid=:aid AND status=2"
      ." ORDER BY lfnr"
    );
    $st_p->bindParam(':aid', $art['id']);
    if (!$st_p->execute()) {
      throw new Exception('Fehler beim Holen der Kommentare zu aid='.$art['id']);
    }
    $posts = $st_p->fetchAll(PDO::FETCH_ASSOC);
    $art['posts'] = $posts;
    
    return $art;
  }
  
  /**
   * posting erzeugen, noch nicht freigeschaltet!
   */
  public function create_post($aid, $username, $usermail, $ptext) {
    // lfnr berechnen
    $stmt = $this->pdo->prepare( "SELECT max(lfnr) lfnr FROM posts WHERE aid=:aid" );
    $stmt->bindParam(':aid', $aid);
    if (!$stmt->execute()) {
      throw new Exception('Fehler beim Zählen der postings zu aid='.$aid);
    }
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $lfnr = isset($row['lfnr']) ? $row['lfnr'] + 1 : 1;
    
    // speichern
    $Helper = new MHelper();
    $code = $Helper->make_code();
    $stmt = $this->pdo->prepare(
      "INSERT INTO posts(aid,lfnr,code,username,usermail,datum,text,status)"
      ." VALUES(:aid,:lfnr,:code,:username,:usermail,SYSDATE(),:text,0)"
    );
    $stmt->bindParam(':aid', $aid);
    $stmt->bindParam(':lfnr', $lfnr);
    $stmt->bindParam(':code', $code);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':usermail', $usermail);
    $stmt->bindParam(':text', $ptext);
    if (!$stmt->execute()) {
      throw new Exception('Fehler beim Aufzeichnen eines Posts');
    }
    $pid = $this->pdo->lastInsertId();
    if (! (int) $pid) {
      throw new Exception('pid konnte nicht ermittelt werden');
    }
    
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
    // check
    if (!$aid = (int) $aid) {
      throw new Exception('No aid given');
    }
    
    // go
    $st = $this->pdo->prepare(
      "SELECT url FROM artikel"
      ." WHERE id=:aid"
    );
    if (!$st->execute(array(':aid' => $aid))) {
      throw new Exception('Fehler beim Holen der URL zu aid='.$aid);
    }
    $row = $st->fetch(PDO::FETCH_ASSOC);
    return $row['url'];
  }
  
  
  /**
   * Neuen Artikel anlegen
   * @return int = article ID
   */
  public function create() {
    $sql = "INSERT INTO artikel(datum,status) VALUES(SYSDATE(),0)";
    $stmt = $this->pdo->prepare($sql);
    if (!$stmt->execute()) {
      throw new Exception('Fehler bei '.$sql);
    }
    
    // get ID
    $aid = $this->pdo->lastInsertId();
    if (! (int) $aid) {
      throw new Exception('aid konnte nicht ermittelt werden');
    }
    return $aid;
  }
  
  
  /**
   * posting bestätigen
   */
  public function confirm_post($pid, $code) {
    $stmt = $this->pdo->prepare("SELECT status, aid, usermail FROM posts WHERE id=:pid AND code=:code");
    $stmt->bindParam(':pid', $pid);
    $stmt->bindParam(':code', $code);
    if (!$stmt->execute()) {
      throw new Exception('Fehler beim Suchen nach Postings mit pid='.$pid.', code='.$code);
    }
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!isset($post['status'])) {
      throw new Exception('Es existiert kein Posting mit pid='.$pid.', code='.$code);
    }
    switch ($post['status']) {
    case 0:
      // ok, jetzt status setzen
      $stmt = $this->pdo->prepare("UPDATE posts SET status=1 WHERE id=:pid" );
      $stmt->bindParam(':pid', $pid);
      if (!$stmt->execute()) {
        throw new Exception('Fehler beim Freischalten des Postings pid='.$pid);
      }
      break;
      
      // an mich mailen
      $mtext = 'Bestätigung der E-Mail-Adresse von '.$post['username'].': '.$post['usermail']."\n\n"
        .'"'.$post['text'].'"'."\n\n"
        .'Beitrag freischalten:'."\n\n"
        .BASEURL.'adm'."\n\n"
      ;
      $e = new Email();
      $e->mailen(EMAIL_ADMIN, 'fs-blog.de: Freischaltung', $mtext);
      
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
