<?
require_once 'Testcase.php';
require_once 'M/MArtikel.php';

class MArtikel_Test extends Testcase {
  
  public function test_01() {
    $m = new MArtikel();
    $this->assertNotNull($m);
  }
  
  
  /**
   * Testing get_all()
   */
  public function ga01() {
    // count must be ok, one row must match exactly
    $this->exec_sqls(array(
      "DELETE FROM artikel WHERE id IN(1,2)",
      "INSERT INTO artikel(id, titel,metadesc,    datum,   text,status)"
      ." VALUES(            1,'tit1',  desc1',SYSDATE(),'text1',     1)",
      "INSERT INTO artikel(id, titel,metadesc,    datum,   text,status)"
      ." VALUES(            2,'tit2',  desc2',SYSDATE(),'text2',     0)"
    ));
    $stmt = $this->pdo->prepare(
      "SELECT count(*) anz FROM artikel"
      ." WHERE status=1"
    );
    if (!$stmt->execute()) {
      $this->fail('preparation not working');
    }
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    $anz = $res['anz'];

    $m = new MArtikel();
    $erg = $m->get_all();
    $this->assertSame($anz, count($erg));
    $b_found = false;
    foreach ($erg as $row) {
      if ($row['id'] == 1) {
        $b_found = true;
        $this->assertSame('tit1', $row['titel']);
        $this->assertSame('desc1', $row['metadesc']);
      }
      if ($row['id'] == 2) {
        $this->fail('row 2 must not turn up in the result');
      }
    }
    $this->assertTrue($b_found);
  }
  
  
  /**
   * testing get_top()
   */
  public function test_gt01() {
    // without text
    $m = new MArtikel();
    $res = $m->get_top(3);
    $this->assertSame(3, count($res));
    $this->assertFalse(isset($res[0]['text']));
  }
  
  public function test_gt02() {
    // with text
    $m = new MArtikel();
    $res = $m->get_top(2, true);
    $this->assertSame(2, count($res));
    $this->assertTrue(isset($res[0]['text']));
  }
  
  
  /**
   * testing get_artikel_komplett_by_url()
   */
  public function test_gu01() {
    // all ok
    $this->prep_artikel(1);
    $m = new MArtikel();
    $res = $m->get_artikel_komplett_by_url('testurl');
    $this->check_artikel_komplett($res);
  }
  
  public function test_gu02() {
    // freigeschaltet, User nicht fürs Backend angemeldet => Artikel kommt
    $this->prep_artikel(1);
    $_SESSION['ok'] = '';
    $m = new MArtikel();
    $res = $m->get_artikel_komplett_by_url('testurl');
    $this->check_artikel_komplett($res);
  }
  
  public function test_gu03() {
    // nicht freigeschaltet, aber User ist fürs Backend angemeldet
    $this->prep_artikel(0);
    $_SESSION['ok'] = true;
    $m = new MArtikel();
    $res = $m->get_artikel_komplett_by_url('testurl');
    $this->check_artikel_komplett($res);
  }
  
  public function test_gu04() {
    // artikel nicht freigeschaltet UND user nicht angemeldet
    $this->prep_artikel(0);
    $_SESSION['ok'] = false;
    $m = new MArtikel();
    $msg = '';
    try {
      $res = $m->get_artikel_komplett_by_url('testurl');
      $msg = 'error not thrown';
    } catch (Exception $e) {
      $this->assertSame('Dieser Artikel ist nicht freigeschaltet.', $e->getMessage());
    }
    if ($msg) {
      $this->fail($msg);
    }
  }
  
  /**
   * testing get_artikel_komplett()
   */
  public function test_gk01() {
    // all ok
    $this->prep_artikel(1);
    $m = new MArtikel();
    $res = $m->get_artikel_komplett(1);

    $this->check_artikel_komplett($res);
  }
  
  // helper for gk
  private function prep_artikel($status) {
    $this->exec_sqls(array(
      "DELETE FROM artikel WHERE id=1"
    ));
    $stmt = $this->pdo->prepare(
      "INSERT INTO artikel(id, titel,metadesc,    datum, text, status,      url)"
      ." VALUES(            1,'tit1', 'desc1',SYSDATE(),:text,:status,'testurl')"
    );
    $text = 'Text mit <imga id="1"> usw.';
    $stmt->bindParam(':text', $text);
    $stmt->bindParam(':status', $status);
    if (!$stmt->execute()) {
      $this->fail('Fehler beim Anlegen des Artikels');
    }
    // abhängige Datensätze anlegen
    $this->exec_sqls(array(
      "DELETE FROM bilder WHERE id=1",
      "INSERT INTO bilder(id,width,height,  ext)"
      ." VALUES(           1,  150,   100,'jpg')",
      "DELETE FROM posts WHERE aid=1 OR id<=4",
      "INSERT INTO posts(id,aid,lfnr,code,username,  usermail,    datum,text,status)"
      ." VALUES(          1,  1,   7,'a1',    'fs','fs@fs.de',SYSDATE(),'t1',     0)",
      "INSERT INTO posts(id,aid,lfnr,code,username,  usermail,    datum,text,status)"
      ." VALUES(          2,  1,   8,'a2',    'ds','ds@fs.de',SYSDATE(),'t2',     1)",
      "INSERT INTO posts(id,aid,lfnr,code,username,  usermail,    datum,text,status)"
      ." VALUES(          3,  1,   9,'a3',    'ls','ls@fs.de',SYSDATE(),'t3',     2)",
      "INSERT INTO posts(id,aid,lfnr,code,username,  usermail,    datum,text,status)"
      ." VALUES(          4,  1,  10,'a4',    'rs','rs@fs.de',SYSDATE(),'t4',     3)"
    ));
  }
  
  private function check_artikel_komplett($res) {
    $this->assertSame('1', $res['id']);
    $this->assertSame('tit1', $res['titel']);
    $this->assertSame('desc1', $res['metadesc']);
    $this->assertSame('Text mit <imga id="1"> usw.', $res['text']);
    $this->assertSame(1, count($res['bilder']));
    $this->assertSame(array(
      'id' => '1',
      'width' => '150',
      'height' => '100',
      'ext' => 'jpg'
    ), $res['bilder'][0]);
    $this->assertSame(1, count($res['posts']));
    $post = $res['posts'][0];
    unset($post['datum']);
    $this->assertSame(array(
      'id' => '3',
      'aid' => '1',
      'lfnr' => '9',
      'code' => 'a3',
      'username' => 'ls',
      'usermail' => 'ls@fs.de',
      'text' => 't3',
      'status' => '2'
    ), $post);  
  }
  
  
  /**
   * testing create_post()
   */
  public function test_cp01() {
    $this->exec_sqls(array(
      "DELETE FROM posts WHERE aid=1"
    ));
    $m = new MArtikel();
    $m->  create_post(1, 'Fritz', 'fsmail.de', 'text');
    $this->check_db(
      "SELECT count(*) anz FROM posts WHERE aid=1",
      array('anz' => '1')
    );
    $this->check_db(
      "SELECT *, DATE_FORMAT(datum, '%Y%m%d') ymd FROM posts WHERE aid=1",
      array('lfnr' => '1', 'username' => 'Fritz', 'usermail' => 'fsmail.de',
        'text' => 'text', 'status' => '0', 'ymd' => Date('Ymd'))
    );
    
  }
  
  
  /**
   * testing confirm_post()
   */
  public function test_cfp01() {
    // ok, update status to 1
    $this->exec_sqls(array(
      "DELETE FROM posts WHERE aid=1 OR id=1",
      "INSERT INTO posts(id,aid,lfnr,code,username,usermail, datum   ,text ,status)"
      ." VALUES(          1,  1,   1,'fs', 'Fritz', 'fs@de',SYSDATE(),'tt1',     0)"
    ));
    $m = new MArtikel();
    $m->confirm_post(1, 'fs');
    $this->check_db(
      "SELECT * FROM posts WHERE aid=1",
      array('lfnr' => '1', 'username' => 'Fritz', 'usermail' => 'fs@de',
        'text' => 'tt1', 'status' => '1')
    );
  }
  
  public function test_cfp02() {
    // already 1, leave it there
    $this->exec_sqls(array(
      "DELETE FROM posts WHERE aid=1 OR id=1",
      "INSERT INTO posts(id,aid,lfnr,code,username,usermail, datum   ,text ,status)"
      ." VALUES(          1,  1,   1,'fs', 'Fritz', 'fs@de',SYSDATE(),'tt1',     1)"
    ));
    $m = new MArtikel();
    $m->confirm_post(1, 'fs');
    $this->check_db(
      "SELECT * FROM posts WHERE aid=1",
      array('lfnr' => '1', 'username' => 'Fritz', 'usermail' => 'fs@de',
        'text' => 'tt1', 'status' => '1')
    );
  }
  
  public function test_cfp03() {
    // status = 2 => error, leave status alone
    $this->exec_sqls(array(
      "DELETE FROM posts WHERE aid=1 OR id=1",
      "INSERT INTO posts(id,aid,lfnr,code,username,usermail, datum   ,text ,status)"
      ." VALUES(          1,  1,   1,'fs', 'Fritz', 'fs@de',SYSDATE(),'tt1',     2)"
    ));
    $m = new MArtikel();
    $msg = '';
    try {
      $m->confirm_post(1, 'fs');
      $msg = 'expected error not thrown';
    } catch (Exception $e) {
      $this->assertSame('Dieser Beitrag darf nicht freigeschaltet werden.', $e->getMessage());
    }
    if ($msg) {
      $this->fail($msg);
    }
    $this->check_db(
      "SELECT * FROM posts WHERE aid=1",
      array('lfnr' => '1', 'username' => 'Fritz', 'usermail' => 'fs@de',
        'text' => 'tt1', 'status' => '2')
    );
  }
  

  /*
   * tests for delete()
   */
  public function test_d1() {
    $this->exec_sqls(array(
      "DELETE FROM artikel WHERE id=1",
      "INSERT INTO artikel(id)"
      ." VALUES(            1)"
    ));
    $this->check_db(
      "SELECT count(*) cnt FROM artikel WHERE id=1",
      array('cnt' => '1')
    );
    $m = new MArtikel();
    $m->delete(1);
    $this->check_db(
      "SELECT count(*) cnt FROM artikel WHERE id=1",
      array('cnt' => '0')
    );
  }
  
}
