<?
require_once 'fslib.php';

require_once 'Testcase.php';
require_once 'C/CArtikel.php';
require_once 'M/MArtikel.php';

class CArtikel_Test extends Testcase {
  
  public function test_01() {
    $c = new CArtikel();
    $this->assertNotNull($c);
  }
  
  
  public function test_w01() {
    $mart = $this->getMockBuilder('MArtikel')->getMock();
    $mart->expects($this->any())
      ->method('getArtikelKomplett')
      ->will($this->returnValue(23))
    ;
    $v = new CArtikel_Test_V;
    $c = new CArtikel(array('MArtikel' => $mart, 'VArtikel' => $v));
    $c->work(array('aid' => 1), '', '');
    $this->assertSame(23, $v->vdata['artikel']);
  }
  
  public function test_w02() {
    $mart = $this->getMock('MArtikel');
    $mart->expects($this->any())
      ->method('getArtikelKomplettByUrl')
      ->will($this->returnValue('huhu'))
    ;
    $v = new CArtikel_Test_V;
    $c = new CArtikel(array('MArtikel' => $mart, 'VArtikel' => $v));
    $c->work(array('url' => 'hallo'), '', '');
    $this->assertSame('huhu', $v->vdata['artikel']);
  }

}

/**
 * Mock a view object, which allows us to inspect the arguments for display()
 */
class CArtikel_Test_V {
  
  public $errmsg;
  public $vdata;
  
  public function display($errmsg, $vdata) {
    $this->errmsg = $errmsg;
    $this->vdata = $vdata;
  }
  
}

