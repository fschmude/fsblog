<?
require_once 'config.php';

class CArtikel {
  
  public function work($get, $post, $mart, $view) {
    $errmsg = '';
    try {
      if (isset($get['aid']) && $aid = (int) $get['aid']) {
        // call by article id
        $artikel = $mart->get_artikel_komplett($aid);
        
      } elseif (isset($get['url']) && strlen($fakeurl = trim($get['url']))) {
        // call by (faked) url?
        $artikel = $mart->get_artikel_komplett_by_url($fakeurl);
      } else {
        throw new Exception('incorrect call');
      }

      // navi_arts werden in jedem Fall gebraucht
      $navi_arts = $mart->get_top(3);
      
    } catch (Exception $e) {
      $errmsg = $e->getMessage();
      if (DISPLAY_ERRORS) {
        echo $errmsg;
        echo $e->getTraceAsString();
      }
    }
  
    $view->display(array(
      'errmsg' => $errmsg,
      'artikel' => $artikel,
      'navi_arts' => $navi_arts
    ));
  }
  
}
