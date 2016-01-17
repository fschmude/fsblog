<?
require_once 'Testcase.php';
require_once 'C/CStatic.php';

class CStatic_Test extends Testcase {
  
  public function test_01() {
    $v = new CStatic_Test_V;
    $c = new CStatic(array('VStatic' => $v));
    $c->work(array('page' => 'about'), 0, 0);
  }
   
}

class CStatic_Test_V {
  public function display(){
  }
}

