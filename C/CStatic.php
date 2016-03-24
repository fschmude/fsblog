<?
require_once 'C/Controller.php';

class CStatic extends Controller {
  
  public function work($get, $post, $files) {
  //public function work($mart, $view, $page) {
    $errmsg = $navi_arts = '';
    try {
      if (!$page = $get['page']) {
        throw new Exception('No page given');
      }
      if (!in_array($page, array('about', 'kontakt'))) {
        throw new Exception('Ungültige page: "'.$page.'"');
      }
      
      // navi_arts werden in jedem Fall gebraucht
      $mart = $this->getObject('MArtikel');
      $navi_arts = $mart->getTop(5);
      
    } catch (Exception $e) {
      $errmsg = $this->handleError($e);
    }
    
    // display
    $view = $this->getObject('VStatic');
    $view->display($errmsg, array('navi_arts' => $navi_arts, 'page' => $page));
  }
  
}

