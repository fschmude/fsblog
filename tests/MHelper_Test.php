<?
require_once 'Testcase.php';
require_once 'M/MArtikel.php';

class MHelper_Test extends Testcase {
  
  /**
   * testing make_code()
   */
  public function test_mc01() {
    $m = new MHelper();
    $this->assertNotNull($m);
    
    $code = $m->make_code();
    
    $this->assertSame(15, strlen($code));
  }
 
}