<?
require_once 'config.php';

class CListe {
  
  public function work($get, $post, $mart, $vliste, $page) {
    $errmsg = '';
    $arts = array();
    try {
      switch ($page) {
      case 'index':
        $arts = $mart->get_top(5, true);
        break;
        
      case 'alle':
        $arts = $mart->get_all();
        break;
        
      default:
        throw new Exception('Ungültige page: "'.$page.'"');
      }
      
    } catch (Exception $e) {
      $errmsg = $e->getMessage();
      $errtrace = $e->getTraceAsString();
      
      if (!DISPLAY_ERRORS) {
        $errmsg = 'Ein Fehler ist aufgetreten, siehe Error-Log.';
      }
      
    }
    
    // display
    $vliste->display($errmsg, $arts, $page);
  }
  
}
