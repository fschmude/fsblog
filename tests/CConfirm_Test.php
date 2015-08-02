<?
require_once 'Testcase.php';
require_once 'C/CConfirm.php';
require_once 'M/MArtikel.php';
require_once 'V/VConfirm.php';

class CConfirm_Test extends Testcase {
  
  public function test_01() {
    // no input
    $v = new CConfirm_VMock();
    $get = array();
    $post = array();
    $c = new CConfirm();
    $c->work($get, $post, null, null, $v);
    $this->assertSame('Input-Variablen stimmen nicht...', $v->errmsg);
    $this->assertSame('', $v->titel);
    $this->assertSame(0, $v->displaymode);
  }
  
  public function test_02() {
    // new post with no text
    $v = new CConfirm_VMock();
    $mart = $this->getMock('MArtikel');
    $get = array();
    $post = array('aid' => 1,
      'username' => 'fs',
      'usermail' => 'fs@fs.de',
      'ptext' => ' '
    );
    $c = new CConfirm();
    $c->work($get, $post, $mart, null, $v);
    $this->assertSame('', $v->errmsg);
    $this->assertSame('Es wurde kein Posting eingetippt.', $v->msg);
    $this->assertSame('Kommentar erfasst', $v->titel);
    $this->assertSame(0, $v->displaymode);
  }
  
  public function test_03() {
    // new post with no email
    $v = new CConfirm_VMock();
    $mart = $this->getMock('MArtikel');
    $get = array();
    $post = array('aid' => 1,
      'username' => 'fs',
      'usermail' => '',
      'ptext' => 'hallo'
    );
    $c = new CConfirm();
    $c->work($get, $post, $mart, null, $v);
    $this->assertSame('', $v->errmsg);
    $this->assertSame('Keine E-Mail-Adresse angegeben.', $v->msg);
    $this->assertSame('Kommentar erfasst', $v->titel);
    $this->assertSame(0, $v->displaymode);
  }
  
  public function test_04() {
    // new post with invalid email
    $v = new CConfirm_VMock();
    $mart = $this->getMock('MArtikel');
    $get = array();
    $post = array('aid' => 1,
      'username' => 'fs',
      'usermail' => 'fs@de',
      'ptext' => 'hallo'
    );
    $c = new CConfirm();
    $c->work($get, $post, $mart, null, $v);
    $this->assertSame('', $v->errmsg);
    $this->assertSame('"fs@de" scheint keine gültige E-Mail-Adresse zu sein.', $v->msg);
    $this->assertSame('Kommentar erfasst', $v->titel);
    $this->assertSame(0, $v->displaymode);
  }
  
  public function test_05() {
    // new post ok
    $v = new CConfirm_VMock();
    $mart = $this->getMock('MArtikel');
    $mart->expects($this->any())
      ->method('create_post')
      ->will($this->returnValue(23))
    ;
    $get = array();
    $post = array('aid' => 1,
      'username' => 'fs',
      'usermail' => 'fs@fs.de',
      'ptext' => 'hallo'
    );
    $c = new CConfirm();
    $c->work($get, $post, $mart, null, $v);
    $this->assertSame('', $v->errmsg);
    $this->assertSame('', $v->msg);
    $this->assertSame('Kommentar erfasst', $v->titel);
    $this->assertSame(VCONFIRM_DISPLAYMODE_POSTMAIL, $v->displaymode);
  }
  
  public function test_06() {
    // confirm post, ok
    $v = new CConfirm_VMock();
    $mart = $this->getMock('MArtikel');
    $mart->expects($this->any())
      ->method('confirm_post')
      ->will($this->returnValue(23))
    ;
    $get = array(
      'pid' => 1,
      'code' => 'abc'
    );
    $post = array();
    $c = new CConfirm();
    $c->work($get, $post, $mart, null, $v);
    $this->assertSame('', $v->errmsg);
    $this->assertSame('', $v->msg);
    $this->assertSame('Bestätigung Mail-Adresse', $v->titel);
    $this->assertSame(VCONFIRM_DISPLAYMODE_POSTCONFIRM, $v->displaymode);
  }
  
  public function test_07() {
    // confirm post, no code
    $v = new CConfirm_VMock();
    $mart = $this->getMock('MArtikel');
    $get = array(
      'pid' => 1,
      'code' => ''
    );
    $post = array();
    $c = new CConfirm();
    $c->work($get, $post, $mart, null, $v);
    $this->assertSame('', $v->errmsg);
    $this->assertSame('Es wurde kein Bestätigungs-Code angegeben.', $v->msg);
    $this->assertSame('Bestätigung Mail-Adresse', $v->titel);
    $this->assertSame(0, $v->displaymode);
  }
  
  public function test_08() {
    // create leser, email invalid
    $v = new CConfirm_VMock();
    $mart = $this->getMock('MArtikel');
    $mleser = $this->getMock('MLeser');
    $get = array();
    $post = array('lmail' => 'abc');
    $c = new CConfirm();
    $c->work($get, $post, $mart, $mleser, $v);
    $this->assertSame('', $v->errmsg);
    $this->assertSame('"abc" ist keine gültige E-Mail-Adresse.', $v->msg);
    $this->assertSame('Erfassung Mail-Adresse', $v->titel);
    $this->assertSame(0, $v->displaymode);
  }
  
  public function test_09() {
    // create leser, email ok
    $v = new CConfirm_VMock();
    $mart = $this->getMock('MArtikel');
    $mleser = $this->getMock('MLeser');
    $get = array();
    $post = array('lmail' => 'fs@fs.de');
    $c = new CConfirm();
    $c->work($get, $post, $mart, $mleser, $v);
    $this->assertSame('', $v->errmsg);
    $this->assertSame('', $v->msg);
    $this->assertSame('Erfassung Mail-Adresse', $v->titel);
    $this->assertSame(VCONFIRM_DISPLAYMODE_LMAIL, $v->displaymode);
  }
  
}

/**
 * Mock a view object, which allows us to inspect the arguments for display()
 */
class CConfirm_VMock {
  
  public $errmsg;
  public $msg;
  public $titel;
  public $displaymode;
  
  public function display($errmsg, $msg, $titel, $navi_arts, $displaymode, $misc) {
    $this->errmsg = $errmsg;
    $this->msg = $msg;
    $this->titel = $titel;
    $this->displaymode = $displaymode;
  }
  
}