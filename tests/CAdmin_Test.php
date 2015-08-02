<?
require_once 'fslib.php';
require_once 'Testcase.php';
require_once 'C/CAdmin.php';

class CAdmin_Test extends Testcase {
  
  // tests for run()
  public function test_r01() {
    // no input
    $v =  new CAdmin_VMock();
    $get = $post = $files = array();
    $c = new CAdmin(array('VAdminStart' => $v));
    $c->run($get, $post, $files);
    $v->check('', array('msg' => 'Bitte Passwort eingeben.'));
  }
  
  public function test_r02() {
    // bogus password
    $this->warten();
    $v =  new CAdmin_VMock();
    $get = $files = array();
    $post = array('pass' => 'bogus');
    $c = new CAdmin(array('VAdminStart' => $v));
    $c->run($get, $post, $files);
    $v->check('', array('msg' => 'Falsches Passwort.'));
    
    // login refracture
    $c->run($get, $post, $files);
    $v->check('', array('msg' => 'Bitte nur ein Login-Versuch alle 10 Sekunden.'));
  }
  
  public function test_r03() {
    // correct password, VAdminArtikelList must get used now
    $this->warten();
    $v =  new CAdmin_VMock();
    $get = $files = array();
    $post = array('pass' => BACKEND_PASSWORD);
    $c = new CAdmin(array('VAdminArtikelList' => $v));
    $c->run($get, $post, $files);
    $stmt = $this->pdo->prepare("SELECT count(*) cnt FROM artikel");
    if (!$stmt->execute()) {
      $this->fail('Fehler beim Zählen der Artikel-Datensätze');
    }
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    $cnt = $res['cnt'];
    $this->assertSame('', $v->errmsg);
    $this->assertEquals($cnt, count($v->data));
  }
  
  
  private function warten() {
    $wait = LOGIN_REFRAK + 1;
    fwrite(STDERR, '[warte '.$wait.' Sekunden]');
    flush();
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

