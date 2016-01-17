<?
require_once 'C/CController.php';

class CRss extends CController {
  
  public function work($get, $post, $files) {
    $errmsg = '';
    $arts = array();
    try {
      $arts = $this->getObject('MArtikel')->getTop(10, true);
    } catch (Exception $e) {
      $errmsg = $e->getMessage();
    }
    
    $this->getObject('VRss')->display($errmsg, $arts);
  }
  
}

