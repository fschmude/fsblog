<?
require_once 'C/Controller.php';

class CArtikel extends Controller {
  
  public function work($get, $post, $files) {
    $errmsg = $vdata = '';
    try {
      $mart = $this->getObject('MArtikel');
      $view = $this->getObject('VArtikel');
      if (isset($get['aid']) && $aid = (int) $get['aid']) {
        // call by article id
        $vdata = $mart->getUrl($aid);
        $view = $this->getObject('VRedirect');
        
      } elseif (isset($get['url']) && strlen($fakeurl = trim($get['url']))) {
        // call by (faked) url?
        $artikel = $mart->getArtikelKomplettByUrl($fakeurl);
        $navi_arts = $mart->getTop(5);
        $vdata = array(
          'artikel' => $artikel,
          'navi_arts' => $navi_arts
        );
      } else {
        throw new Exception('incorrect call');
      }

    } catch (Exception $e) {
      $errmsg = $this->handleError($e);
    }
  
    $view->display($errmsg, $vdata);
  }
  
}

