<?
require_once 'C/CController.php';

class CMailtext extends CController {
  
  public function run($get) {
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
      
      $data = $m->getTeaser($get['aid']);
      
    } catch (Exception $e) {
      $errmsg = $e->getMessage();
      $data = 'Sonstiger Fehler';
    }
    
    // Now display our findings
    $v->display($errmsg, $data);
  }

}

