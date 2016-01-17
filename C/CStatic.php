<?
require_once 'C/CController.php';

class CStatic extends CController {
  
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
      $navi_arts = $mart->getTop(3);
      
    } catch (Exception $e) {
      $errmsg = $e->getMessage();
    }
    
    // display
    $view = $this->getObject('VStatic');
    $view->display($errmsg, array('navi_arts' => $navi_arts, 'page' => $page));
  }
  
}

