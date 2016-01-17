<?
require_once 'C/CController.php';

class CListe extends CController {
  
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
      $errmsg = $e->getMessage();
      $errtrace = $e->getTraceAsString();
      
      if (!DISPLAY_ERRORS) {
        $errmsg = 'Ein Fehler ist aufgetreten, siehe Error-Log.';
      }
      
    }
    
    // display
    $vliste = $this->getObject('VListe');
    $vliste->display($errmsg, $vdata);
  }
  
}

