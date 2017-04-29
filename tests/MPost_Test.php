<?
require_once 'Testcase.php';
require_once PATH_PRIVATE.'M/MPost.php';

class MPost_Test extends Testcase {
  
  public function test_01() {
    $m = new MPost();
    $this->assertNotNull($m);
  }
  
  
  /**
   * testing create_post()
   */
  public function test_cp01() {
    $this->execSqls(array(
      "DELETE FROM posts WHERE aid=1"
    ));
    $mail = new MPost_Test_MEmail();
    $m = new MPost(array('MEmail' => $mail));
    $m->createPost(1, 'Fritz', 'fsmail.de', 'text');
    $this->checkDb(
      "SELECT count(*) anz FROM posts WHERE aid=1",
      array('anz' => '1')
    );
    $this->checkDb(
      "SELECT *, DATE_FORMAT(datum, '%Y%m%d') ymd FROM posts WHERE aid=1",
      array('lfnr' => '1', 'username' => 'Fritz', 'usermail' => 'fsmail.de',
        'text' => 'text', 'status' => '0', 'ymd' => Date('Ymd'))
    );
    $this->assertTrue($mail->mailenCalled);
  }
  
  
  /**
   * testing confirm_post()
   */
  public function test_cfp01() {
    // ok, update status to 1
    $this->execSqls(array(
      "DELETE FROM posts WHERE aid=1 OR id=1",
      "INSERT INTO posts(id,aid,lfnr,code,username,usermail, datum   ,text ,status)"
      ." VALUES(          1,  1,   1,'fs', 'Fritz', 'fs@de',SYSDATE(),'tt1',     0)"
    ));
    $mail = new MPost_Test_MEmail;
    $m = new MPost(array('MEmail' => $mail));
    $m->confirmPost(1, 'fs');
    $this->checkDb(
      "SELECT * FROM posts WHERE aid=1",
      array('lfnr' => '1', 'username' => 'Fritz', 'usermail' => 'fs@de',
        'text' => 'tt1', 'status' => '1')
    );
    $this->assertTrue($mail->mailenCalled);
  }
  
  public function test_cfp02() {
    // already 1, leave it there
    $this->execSqls(array(
      "DELETE FROM posts WHERE aid=1 OR id=1",
      "INSERT INTO posts(id,aid,lfnr,code,username,usermail, datum   ,text ,status)"
      ." VALUES(          1,  1,   1,'fs', 'Fritz', 'fs@de',SYSDATE(),'tt1',     1)"
    ));
    $m = new MPost();
    $m->confirmPost(1, 'fs');
    $this->checkDb(
      "SELECT * FROM posts WHERE aid=1",
      array('lfnr' => '1', 'username' => 'Fritz', 'usermail' => 'fs@de',
        'text' => 'tt1', 'status' => '1')
    );
  }
  
  public function test_cfp03() {
    // status = 2 => error, leave status alone
    $this->execSqls(array(
      "DELETE FROM posts WHERE aid=1 OR id=1",
      "INSERT INTO posts(id,aid,lfnr,code,username,usermail, datum   ,text ,status)"
      ." VALUES(          1,  1,   1,'fs', 'Fritz', 'fs@de',SYSDATE(),'tt1',     2)"
    ));
    $m = new MPost();
    $msg = '';
    try {
      $m->confirmPost(1, 'fs');
      $msg = 'expected error not thrown';
    } catch (Exception $e) {
      $this->assertSame('Dieser Beitrag darf nicht freigeschaltet werden.', $e->getMessage());
    }
    if ($msg) {
      $this->fail($msg);
    }
    $this->checkDb(
      "SELECT * FROM posts WHERE aid=1",
      array('lfnr' => '1', 'username' => 'Fritz', 'usermail' => 'fs@de',
        'text' => 'tt1', 'status' => '2')
    );
  }
  
}

/**
 * Mock objects
 */
class MPost_Test_MEmail {
  
  public $mailenCalled = false;
  
  public function mailen() {
    $this->mailenCalled = true;
  }

}

