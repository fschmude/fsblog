<?
require_once PATH_PRIVATE.'M/Model.php';

class MSnippet extends Model {
  
  private $dobj = null;
  
  /**
   * Konstruktor
   */
  public function __construct($objs = array()) {
    $this->dobj = $this->getObject('DSnips');
    parent::__construct($objs);
  }
  
                                              
  /**
   * getList: Besonderheit: zusätzlicher Parameter für filtern
   * @param int filter = 201604
   */
  public function getList($filter = 0) {
    if (!$month = (int) $filter) {
      $month = date('Ym');
    }
    $rows = $this->dobj->getList($month);
    foreach ($rows as &$row) {
      if (strlen($row['text']) > 80) {
        $row['text'] = substr($row['text'], 0, 77).'...';
      }
      $row['url'] = $this->completeUrl('', $row['id']);
    }
    $list = array(
      'month' => $month,
      'rows' => $rows,
      'allMonths' => $this->dobj->getMonths()
    );
    return $list;
  }
  
  
  /**
   * einen Schnippel holen
   */
  public function getItem($id) {
    // DB-Eintrag
    $row = $this->dobj->getRow($id);
    $row['month'] = date('Ym', strtotime($row['datum']));
    $row['url'] = $this->completeUrl('', $row['id']);
    
    // imgas
    $mBild = $this->getObject('MBild');
    $row['lastImgas'] = $mBild->getLastImgas();
    
    return $row;
  }
  
  
  /**
   * einen Schnippel editieren
   */
  public function edit($snip) {
    $this->dobj->edit($snip);
  }
  
  
  /**
   * einen Schnippel löschen
   */
  public function delete($id) {
    $this->dobj->delete($id);
  }
   
  
  /**
   * create
   */
  public function create() {
    $newid = $this->dobj->createValues(array(
      'datum' => Date('Y-m-d H:i'),
      'text' => '',
      'fbid' => ''
    ));
    return $newid;
  }
  
  
  /**
   * get month of snippets
   */
  public function getMonat($monat) {
    // get all snippets of this month
    $rows = $this->dobj->getMonat($monat);
    
    // build continuous text
    $text = 'Hier sind meine Tageseinträge von '.$monat.'.'."\n\n";
    foreach ($rows as $row) {
      $text .= '--'."\n"
      .$row['datum']
      .' (<a href="'.$this->completeUrl(0, $row['id'])
      .'">Direktlink</a>'
      ;
      if ($row['fbid']) {
        $fblink = $this->makeFbUrl($row['fbid']);
        $text .= ', <a href="'.$fblink.'" target="_blank">kommentieren bei Facebook</a>';
      }
      $text .= ")\n\n"
      .$row['text']
      ."\n\n"
      ;
    }
    
    // datum
    $dtM = new DateTime($monat.'-01');
    $dtM->add(new DateInterval('P1M'));
    $dtM->sub(new DateInterval('P1D'));
    $monatsLetzter = $dtM->format('Y-m-d');
    
    // build an "article" of all this
    $fakeArt = array(
      'type' => 'monat',
      'titel' => 'Einträge von '.$monat,
      'metadesc' => 'Alle Tageseinträge von '.$monat,
      'datum' => $monatsLetzter,
      'text' => $text,
      'url' => $this->completeUrl(0, 0, $monat)
    );
    
    // add images and vids
    $addit = $this->getEmbeddedRows($fakeArt['text']);
    $fakeArt['bilder'] = $addit['bilder'];
    $fakeArt['vids'] = $addit['vids'];
    
    return $fakeArt;
  }
  
  
  /**
   * make Facebook Link
   * @access public for testing only
   */
  public function makeFbUrl($fbid) {
    if ($fbid) {
      $url = 'https://www.facebook.com/fritz.schmude/posts/'.$fbid;
    } else {
      $url = '';
    }
    return $url;
  }
  
  
  /**
   * get whole snippet for showing it
   */
  public function getSnippet($sid) {
    // check
    if (!$sid = (int) $sid) {
      throw new Exception('Keine sid angegeben.');
    }
    
    // go
    $row = $this->dobj->getRow($sid);
    $snip = array();
    $snip['datum'] = $row['datum'];
    $snip['text'] = $row['text'];
    $snip['titel'] = $snip['metadesc'] = 'Eintrag von '.date('Y-m-d H:i', strtotime($snip['datum']));
    $snip['url'] = $this->completeUrl(0, $sid);
    $snip['urlBefore'] = $this->completeUrl(0, $this->dobj->getBefore($sid));
    $snip['urlAfter'] = $this->completeUrl(0, $this->dobj->getAfter($sid));
    $snip['urlMonat'] = $this->completeUrl(0, 0, date('Y-m', strtotime($snip['datum'])));
    $snip['urlFB'] = $this->makeFbUrl($row['fbid']);
    
    // add images and vids
    $addit = $this->getEmbeddedRows($snip['text']);
    $snip['bilder'] = $addit['bilder'];
    $snip['vids'] = $addit['vids'];
    
    // temporary hack as long as we dont allow comments for snippets
    $snip['type'] = 'snippet';
    
    return $snip;
  }
    
}
