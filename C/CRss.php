<?
require_once 'config.php';

class CRss {
  
  public function work($mart, $view) {
    $errmsg = '';
    try {
      $arts = $mart->get_top(10, true);
    } catch (Exception $e) {
      $errmsg = $e->getMessage();
    }
    
    $view->display($errmsg, $arts);
  }
  
}