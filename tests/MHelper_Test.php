<?
require_once 'Testcase.php';
require_once 'M/MArtikel.php';

class MHelper_Test extends Testcase {
  
  /**
   * testing makeCode()
   */
  public function test_mc01() {
    $m = new MHelper();
    $this->assertNotNull($m);
    
    $code = $m->makeCode();
    
    $this->assertSame(15, strlen($code));
  }
 
}

