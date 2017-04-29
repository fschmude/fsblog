<?
require_once 'Testcase.php';
require_once PATH_PRIVATE.'M/MSnippet.php';

class MSnippet_Test extends Testcase {
  
  public function test_01() {
    $m = new MSnippet();
    $this->assertNotNull($m);
  }
  
  
  /*
   * tests for getSnippet()
   */
  public function test_gs1() {
    // error, falls sid ungültig
    $m = new MSnippet();
    try {
      $m->getSnippet(0);
      $thrown = false;
    } catch (Exception $e) {
      $this->assertSame('Keine sid angegeben.', $e->getMessage());
      $thrown = true;
    }
    $this->assertTrue($thrown, 'Error nicht ausgelöst');
  }
  
  
}

