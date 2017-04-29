<?
require_once 'Testcase.php';
require_once PATH_PRIVATE.'C/CAdmin.php';

class CAdmin_Test extends Testcase {
  
  // tests for run()
  public function test_r01() {
    // no input
    $v =  new CAdmin_VMock();
    $get = $post = $files = array();
    $c = new CAdmin(array('VAdminStart' => $v));
    $c->work($get, $post, $files);
    $v->check('', array('msg' => 'Bitte Passwort eingeben.'));
  }
  
  public function test_r02() {
    // bogus password
    $this->warten();
    $v =  new CAdmin_VMock();
    $get = $files = array();
    $post = array('pass' => 'bogus');
    $c = new CAdmin(array('VAdminStart' => $v));
    $c->work($get, $post, $files);
    $v->check('', array('msg' => 'Falsches Passwort.'));
    
    // login refracture
    $c->work($get, $post, $files);
    $v->check('', array('msg' => 'Bitte nur ein Login-Versuch alle '.LOGIN_REFRAK.' Sekunden.'));
  }
  
  
  private function warten() {
    $wait = LOGIN_REFRAK + 1;
    fwrite(STDERR, '[warte '.$wait.' Sekunden]');
    sleep($wait);
  }
  
}

class CAdmin_VMock {
  public $errmsg;
  public $data;
  
  public function display($errmsg, $data) {
    $this->errmsg = $errmsg;
    $this->data = $data;
  }
  
  public function check($err_soll, $data_soll) {
    Testcase::assertSame($err_soll, $this->errmsg);
    Testcase::assertSame($data_soll, $this->data);
  }

}

