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
    $this->check_db(
      "SELECT * FROM bilder WHERE id=".$id,
      $row
    );
  }
  
  
}

