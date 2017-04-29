<?
require_once 'Testcase.php';
require_once PATH_PRIVATE.'M/MBild.php';

class MBild_Test extends Testcase {
  
  /**
   * test editing
   */
  public function test_e01() {
    // nur Datenänderung, ohne upload
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
    $m->edit($row, 0);
    $this->checkDb(
      "SELECT * FROM bilder WHERE id=".$id,
      $row
    );
  }
  
  public function test_e02() {
    // mit upload, daten müssen aus dem upload übernommen werden

    // Testbild erzeugen (gif, 10x10 Pixel)
    $base64 = 'R0lGODlhCgAKAKECAAAAAP//AP///////yH+CGZzIGZlY2l0ACwAAAAACgAKAAACGpSPAsurEKIEKsIAmdz4NrtM3nOBh+YgjVEAADs=';
    file_put_contents(PATH_PRIVATE.'imga/testbild', base64_decode($base64));
    
    $m = new MBild;
    $id = $m->create();
    $row = array(
      'id' => $id,
      'ext' => 0,
      'alt' => 'Haha',
      'url' => 0,
      'width' => 0,
      'height' => 0
    );
    $upfile = array(
      'size' => 100, 
      'tmp_name' => PATH_PRIVATE.'imga/testbild',
      'error' => '0',
      'type' => 'image/gif'
    );
    $m->edit($row, $upfile);
    $soll = array(
      'ext' => 'gif',
      'alt' => 'Haha',
      'url' => $id,
      'width' => '10',
      'height' => '10'
    );
    $this->checkDb(
      "SELECT * FROM bilder WHERE id=".$id,
      $soll
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

