<?
require_once 'Testcase.php';
require_once PATH_PRIVATE.'M/MLeser.php';

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
    $this->execSqls(array(
      "DELETE FROM leser WHERE lmail='huhu'"
    ));
    $mail = new MLeser_Test_MEmail();
    $m = new MLeser(array('MEmail' => $mail));
    $m->createLeser('huhu');
    $row = $this->checkDb(
      "SELECT DATE_FORMAT(datum, '%Y%m%d') datum, status FROM leser WHERE lmail='huhu'",
      array('datum' => Date('Ymd'), 'status' => '0')
    );
    $this->assertTrue($mail->mailenCalled);
  }
  
  
  /**
   * testing confirm()
   */
  public function test_cfm_01() {
    // code doesnt exist
    $this->execSqls(array(
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
    $this->execSqls(array(
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
    $this->execSqls(array(
      "DELETE FROM leser WHERE lmail='huhu' OR code='12a'",
      "INSERT INTO leser(lmail,    datum, code,status)"
      ." VALUES(        'huhu',SYSDATE(),'12a',     0)"
    ));
    $ret = $m->confirm('12a');
    $this->assertSame('huhu', $ret);
    $row = $this->checkDb(
      "SELECT status FROM leser WHERE lmail='huhu'",
      array('status' => '1')
    );
  }
  
  
  /**
   * getMaildata()
   */
  public function test_gt1() {
    $m = new MLeser();
    $this->execSqls(array(
      "DELETE FROM artikel WHERE id=1"
    ));
    $msg = '';
    try {
      $m->getMaildata(1);
      $msg = 'Error not thrown!';
    } catch (Exception $e) {
      $this->assertSame('Kein Datensatz Nr. 1 in artikel', $e->getMessage());
    }
    if ($msg) $this->fail('Error not thrown');
  }
  
  public function test_gt2() {
    // all ok
    $m = new MLeser();
    $this->execSqls(array(
      "DELETE FROM artikel WHERE id=1",
      "INSERT INTO artikel(id,titel,text,status) VALUES(1,'Testtitel', 'Dies ist ein Testartikel',1)",
      "DELETE FROM leser WHERE id IN(1,2) OR lmail IN('m1','m2')",
      "INSERT INTO leser(id,lmail,code,datum,status) VALUES(1,'m1','123','1969-03-29 23:45:00', 1)",
      "INSERT INTO leser(id,lmail,code,datum,status) VALUES(2,'m2','123','1969-03-29 23:45:00', 0)"
    ));
    $a = $m->getMaildata(1);
    $this->assertSame('Testtitel', $a['titel']);
    $b1Found = $b2Found = false;
    $aLeser = explode(',', $a['leser']);
    foreach ($aLeser as $lmail) {
      if ($lmail == 'm1') {
        $b1Found = true;
      }
      if ($lmail == 'm2') {
        $b2Found = true;
      }
    }
    $this->assertTrue($b1Found, 'Aktiver Leser nicht in Ergebnismenge!');
    $this->assertFalse($b2Found, 'Unbestätigter Leser in Ergebnismenge!');
  }
  
}

/**
 * Mock objects
 */
class MLeser_Test_MEmail {
  
  public $mailenCalled = false;
  
  public function mailen() {
    $this->mailenCalled = true;
  }

}

