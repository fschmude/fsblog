<?
require_once PATH_PRIVATE.'T/TInjectable.php';

abstract class Model {
  
  use TInjectable;

  /**
   * make the complete, canonical URL for an article/snip/month
   */
  public function completeUrl($url, $sid = 0, $month = '') {
    if ($url = trim($url)) {
      $curl = BASEURL.'artikel/'.$url.'.htm';
    } elseif ($sid = (int) $sid) {
      $curl = BASEURL.'snip/'.$sid;
    } elseif ($month = trim($month)) {
      $curl = BASEURL.'monat/'.$month;
    } else {
      $curl = '';
    }
    return $curl;
  }
  

  /**
   * Complete URLs of a whole array
   * @param byref array $rows = numeric array of rows, 
   *    each row = array( ..., 'url' => raw url, ...);
   */
  protected function completeAllUrls(&$rows) {
    foreach ($rows as &$row) {
      $row['url'] = $this->completeUrl($row['url']);
    }
  }
  
  
  /**
   * Postings und Bilder-Infos aus Text extrahieren
   * @param string $text = 'hier <imga id="34"> sehen sie...'
   * @return array(
   *    'bilder' => array()
   *    'vids' => array()
   * );
   */
  protected function getEmbeddedRows($text) {
    // hat's Bilder?
    $dbilder = $this->getObject('DBilder');
    $result['bilder'] = array();
    $pos = 0;
    $search = '<imga id="';
    while (($pos = strpos($text, $search, $pos)) !== false) {
      $pos_e = strpos($text, '>', $pos + 10);
      $bid = substr($text, $pos + 10, $pos_e - $pos - 11);
      $bild = array(
        'id' => $bid,
        'width' => '100',
        'height' => '50',
        'url' => 'fehler',
        'alt' => 'Kein Bild-Datensatz!',
        'ext' => 'gif'
      );
      if ($bid = (int) $bid) {
        $row = $dbilder->getRow($bid);
        if (isset($row['ext']) && strlen($row['ext'])) {
          $bild = $row;
        }
      }
      $result['bilder'][] = $bild;
      $pos++;
    }
    
    // vids
    $mv = $this->getObject('MVideo');
    $result['vids'] = array();
    $pos = 0;
    $search = '<video id="';
    while (($pos = strpos($text, $search, $pos)) !== false) {
      $pos_e = strpos($text, '>', $pos + 11);
      $vid = substr($text, $pos + 11, $pos_e - $pos - 12);
      $result['vids'][] = $mv->getInfo($vid);
      $pos++;
    }
    
    return $result;
  }
  
  
}

