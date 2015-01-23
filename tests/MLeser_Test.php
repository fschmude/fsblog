<?
require_once 'Testcase.php';
require_once 'M/MLeser.php';

class MLeser_Test extends Testcase {
  
  public function test_01() {
    $m = new MLeser();
    $this->assertNotNull($m);
  }
  
  
  /**
   * testing create_leser()
   */
  public function test_cl_01() {
    // create "mail address" huhu 
    $this->exec_sqls(array(
      "DELETE FROM leser WHERE lmail='huhu'"
    ));
    $m = new MLeser();
    $m->create_leser('huhu');
    $row = $this->check_db(
      "SELECT DATE_FORMAT(datum, '%Y%m%d') datum, status FROM leser WHERE lmail='huhu'",
      array('datum' => Date('Ymd'), 'status' => '0')
    );
  }
  
  
  /**
   * testing confirm()
   */
  public function test_cfm_01() {
    // code doesnt exist
    $this->exec_sqls(array(
      "DELETE FROM leser WHERE code='12a'"
    ));
    $msg = '';
    try {
      $m = new MLeser();
      $m->confirm('12a');
      $msg = 'expected error not thrown';
    } catch (Exception $e) {
      $this->assertSame('Es existiert kein Eintrag mit code="12a"', $e->getMessage());
    }
    if ($msg) {
      $this->fail($msg);
    }
  }
  
  public function test_cfm_02() {
    // status is 2 (whatever that means)
    $this->exec_sqls(array(
      "DELETE FROM leser WHERE code='12a'",
      "INSERT INTO leser(lmail,    datum, code,status)"
      ." VALUES(        'huhu',SYSDATE(),'12a',     2)"
    ));
    $msg = '';
    try {
      $m = new MLeser();
      $m->confirm('12a');
      $msg = 'expected error not thrown';
    } catch (Exception $e) {
      $this->assertSame('Ungültiger Status: 2', $e->getMessage());
    }
    if ($msg) {
      $this->fail($msg);
    }
  }
  
  public function test_cfm_03() {
    // everything ok, confirm address, change status
    $m = new MLeser();
    $pdo = $m->get_pdo();
    $this->exec_sqls(array(
      "DELETE FROM leser WHERE lmail='huhu' OR code='12a'",
      "INSERT INTO leser(lmail,    datum, code,status)"
      ." VALUES(        'huhu',SYSDATE(),'12a',     0)"
    ));
    $ret = $m->confirm('12a');
    $this->assertSame('huhu', $ret);
    $row = $this->check_db(
      "SELECT status FROM leser WHERE lmail='huhu'",
      array('status' => '1')
    );
  }
  
}