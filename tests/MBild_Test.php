<?
require_once 'Testcase.php';
require_once 'M/MBild.php';

class MBild_Test extends Testcase {
  
  /**
   * test editing
   */
  public function test_01() {
    $m = new MBild;
    $id = $m->create();
    $row = array(
      'id' => $id,
      'width' => '99',
      'height' => '99',
      'url' => 'abc',
      'ext' => 'tst',
      'alt' => 'Haha'
    );
    $upfile = array('size' => 100, 'tmp_name' => '1.tst');
    $m->edit($row, $upfile);
    $this->checkDb(
      "SELECT * FROM bilder WHERE id=".$id,
      $row
    );
  }
  
  
  /**
   * getImageInfo
   */
  public function test_gi01() {
    // by id
    $this->execSqls(array(
      "DELETE FROM bilder WHERE id=1 OR url='huhu'",
      "INSERT INTO bilder(id,url,width) VALUES(1,'huhu',99)"
    ));
    $m = new MBild;
    $row = $m->getImageInfo('1');
    $this->assertSame('99', $row['width']);
  }
  
  public function test_gi02() {
    // by url
    $this->execSqls(array(
      "DELETE FROM bilder WHERE id=1 OR url='huhu'",
      "INSERT INTO bilder(id,url,width) VALUES(1,'huhu',99)"
    ));
    $m = new MBild;
    $row = $m->getImageInfo('huhu');
    $this->assertSame('99', $row['width']);
  }
  
}

