<?
require_once 'C/Controller.php';

class CListe extends Controller {
  
  public function work($get, $post, $files) {
    try {
      $errmsg = '';
      $vdata = array('arts' => array(), 'page' => 'index');
      $mart = $this->getObject('MArtikel');
      
      if (isset($get['list']) && $get['list'] == 'alle') {
        // Liste aller Artikel
        $vdata['arts'] = $mart->getAllLive();
        $vdata['page'] = 'alle';
        
      } else {
        // Startseite, nur neueste Artikel
        $vdata['arts'] = $mart->getTop(5, true);
      }
      
    } catch (Exception $e) {
      $errmsg = $this->handleError($e);
    }
    
    // display
    $vliste = $this->getObject('VListe');
    $vliste->display($errmsg, $vdata);
  }
  
}

