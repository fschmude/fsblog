<?
require_once 'Testcase.php';
require_once PATH_PRIVATE.'C/CArtikel.php';

class CArtikel_Test extends Testcase {
  
  public function test_01() {
    $c = new CArtikel();
    $this->assertNotNull($c);
  }
  
  
  public function test_w01() {
    $mart = $this->getMockBuilder('MArtikel')->getMock();
    $mart->expects($this->any())
      ->method('completeUrl')
      ->will($this->returnValue(23))
    ;
    $v = new CArtikel_Test_V;
    $c = new CArtikel(array('MArtikel' => $mart, 'VRedirect' => $v));
    $c->work(array('aid' => 1), '', '');
    $this->assertSame('', $v->errmsg);
    $this->assertSame(23, $v->vdata);
  }
  
  public function test_w02() {
    $mart = $this->getMock('MArtikel');
    $mart->expects($this->any())
      ->method('getArtikelKomplettByUrl')
      ->will($this->returnValue(array(1 => 'huhu')))
    ;
    $v = new CArtikel_Test_V;
    $c = new CArtikel(array('MArtikel' => $mart, 'VArtikel' => $v));
    $c->work(array('url' => 'hallo'), '', '');
    $this->assertSame('', $v->errmsg);
    $this->assertSame('huhu', $v->vdata[1]);
  }
  
  
  /*
   * Tests for addNavi
   */
  public function test_an01() {
    $c = new CArtikel();
    $a = array();
    $c->addNavi($a);
    $this->assertSame(5, count($a['navi_arts']));
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

