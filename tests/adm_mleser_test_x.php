<?
include_once 'Testcase.php';
include_once '../adm/MLeser.php';

class adm_MLeser_Test extends Testcase{
  
  public function test_get_all() {
    $this->exec_sqls(array(
      "DELETE FROM leser WHERE id=1",
      "INSERT INTO leser(id,   lmail,       datum,code,status)"
      ." VALUES(          1,'a@b.de','1969-03-29',   1,     2)"
    ));
    
    $ml = new MLeser();
    $res = $ml->get_all();
    
    $b_found = false;
    foreach ($res as $row) {
      if ($row['id'] == 1) {
        $b_found = true;
        $this->assertSame('a@b.de', $row['lmail']);
        $this->assertSame($row['code'], '1');
        $this->assertSame($row['status'], '2');
      }
    }
    
    $this->assertTrue($b_found);
  }
  
  public function test_delete01() {
    $this->exec_sqls(array(
      "DELETE FROM leser WHERE id=1",
      "INSERT INTO leser(id,   lmail,       datum,code,status)"
      ." VALUES(          1,'a@b.de','1969-03-29',   1,     2)"
    ));
    $this->check_db("SELECT count(*) anz FROM leser WHERE id=1", array('anz' => '1'));
    
    $ml = new MLeser();
    $res = $ml->delete(1);
    $this->assertTrue($res);
    $this->check_db("SELECT count(*) anz FROM leser WHERE id=1", array('anz' => '0'));
  }
  
}
