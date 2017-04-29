<?
require_once 'Testcase.php';
require_once PATH_PRIVATE.'C/CRss.php';

class CRss_Test extends Testcase {
  
  public function test_01() {
    $v = new CRss_Test_V;
    $c = new CRss(array('VRss' => $v));
    $c->work(0, 0, 0);
    $this->assertSame('', $v->errmsg);
    $this->assertInternalType('array', $v->data);
    // falls aktueller Monat gestrichen wurde, 10 Zeilen. Falls noch kein aktueller Monat, dann 11
    $this->assertGreaterThanOrEqual(10, count($v->data));
    $this->assertLessThanOrEqual(11, count($v->data));
  }
  
}

/**
 * Mock Viewer without header sending
 */
class CRSS_Test_V {
  
  public $errmsg;
  public $data;
  
  public function display($errmsg, $data) {
    $this->errmsg = $errmsg;
    $this->data = $data;
  }
}

