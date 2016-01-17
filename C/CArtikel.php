<?
require_once 'C/CController.php';

class CArtikel extends CController {
  
  public function work($get, $post, $files) {
    $errmsg = $artikel = $navi_arts = '';
    try {
      $mart = $this->getObject('MArtikel');
      $view = $this->getObject('VArtikel');
      if (isset($get['aid']) && $aid = (int) $get['aid']) {
        // call by article id
        $artikel = $mart->getArtikelKomplett($aid);
        
      } elseif (isset($get['url']) && strlen($fakeurl = trim($get['url']))) {
        // call by (faked) url?
        $artikel = $mart->getArtikelKomplettByUrl($fakeurl);
      } else {
        throw new Exception('incorrect call');
      }

      // navi_arts werden in jedem Fall gebraucht
      $navi_arts = $mart->getTop(3);
      
    } catch (Exception $e) {
      $errmsg = $e->getMessage();
      if (DISPLAY_ERRORS) {
        echo $errmsg;
        echo $e->getTraceAsString();
      }
    }
  
    $view->display($errmsg, array(
      'artikel' => $artikel,
      'navi_arts' => $navi_arts
    ));
  }
  
}
