<?
require_once 'C/Controller.php';

class CRss extends Controller {
  
  public function work($get, $post, $files) {
    $errmsg = '';
    $arts = array();
    try {
      $arts = $this->getObject('MArtikel')->getTop(11, true);
      
      // Ausnahme: Der aktuelle Monat darf nicht dabei sein. Abonnenten sollen nicht auf unvollständige Monate gestoßen werden.
      foreach ($arts as $key => $art) {
        if (!isset($art['aid']) || !$art['aid']) {
          // Keine aid => es ist ein Monatseintrag
          $datum = date('Y-m').'-01';
          if ($art['datum'] == $datum) {
            unset($arts[$key]);
          }
        }
      }
      
    } catch (Exception $e) {
      $errmsg = $this->handleError($e);
    }
    
    $this->getObject('VRss')->display($errmsg, $arts);
  }
  
}

