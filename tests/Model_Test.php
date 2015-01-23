<?
require_once 'Testcase.php';
require_once 'M/Model.php';

class Model_Test extends Testcase {
  
  public function test_01() {
    $m = new Model();
    $this->assertNotNull($m);
    $this->assertNotNull($m->get_pdo());
  }
  
}