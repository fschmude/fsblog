<?
require_once 'Testcase.php';
require_once PATH_PRIVATE.'M/MVideo.php';

class MVideo_Test extends Testcase {
  
  public function test_01() {
    $m = new MVideo();
    $this->assertNotNull($m);
  }
  
  
  /**
   * testing getInfo()
   */
  // helper
  private function prep_gi($s) {
    $this->execSqls(array(
      "DELETE FROM videos WHERE id=1",
      "INSERT INTO videos(id,width,height,vname)"
      ." VALUES(           1,  100,    80,'fsv')"
    ));
    $this->prep_gi_mf(PATH_PRIVATE.'imga/fsv.mp4', $s == 1 || $s == 3);
    $this->prep_gi_mf(PATH_PRIVATE.'imga/fsv.ogg', $s == 2 || $s == 3);
  }
  private function prep_gi_mf($file, $bCreate) {
    if ($bCreate) {
      exec('date > '.$file);
    } else {
      if (file_exists($file)) {
        unlink($file);
      }
    }
  }
  
  // now the tests (for getInfo)
  public function test_gi01() {
    // keine Dateien
    $this->prep_gi(0);
    $m = new MVideo();
    $row = $m->getInfo(1);
    $this->assertSame($row, array(
      'id' => '1',
      'width' => '100',
      'height' => '80',
      'vname' => 'fsv',
      'sources' => 0
    ));
  }
  public function test_gi02() {
    // nur mp4
    $this->prep_gi(1);
    $m = new MVideo();
    $row = $m->getInfo(1);
    $this->assertSame($row['sources'], 1);
  }
  public function test_gi03() {
    // nur ogg
    $this->prep_gi(2);
    $m = new MVideo();
    $row = $m->getInfo(1);
    $this->assertSame($row['sources'], 2);
  }
  public function test_gi04() {
    // beide Formate
    $this->prep_gi(3);
    $m = new MVideo();
    $row = $m->getInfo(1);
    $this->assertSame($row['sources'], 3);
  }
  
  
}

