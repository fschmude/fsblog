<?
require_once PATH_PRIVATE.'C/Controller.php';

class CMailtext extends Controller {
  
  public function work($get, $post, $files) {
    $data = '';
    $errmsg = '';
    $v = $this->getObject('VMailtext');
    try {
      if (!(isset($_SESSION['ok']) && $_SESSION['ok'])) {
        throw new Exception('Bitte anmelden.');
      }
    
      $m = $this->getObject('MLeser');
      
      if (!isset($get['aid']) || !$aid = $get['aid']) {
        throw new Exception('Keine aid angegeben');
      }
      
      $data = $m->getMaildata($get['aid']);
      
    } catch (Exception $e) {
      $errmsg = $this->handleError($e);
      $data = 'Sonstiger Fehler';
    }
    
    // Now display our findings
    $v->display($errmsg, $data);
  }

}

