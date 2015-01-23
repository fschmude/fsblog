<?
require_once 'config.php';

class CStatic {
  
  public function work($mart, $view, $page) {
    $errmsg = '';
    try {
      if (!in_array($page, array('about', 'kontakt'))) {
        throw new Exception('Ungültige page: "'.$page.'"');
      }
      
      // navi_arts werden in jedem Fall gebraucht
      $navi_arts = $mart->get_top(3);
      
    } catch (Exception $e) {
      $errmsg = $e->getMessage();
    }
    
    // display
    $view->display($errmsg, $navi_arts, $page);
  }
  
}
