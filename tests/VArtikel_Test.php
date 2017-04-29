<?
require_once 'Testcase.php';
require_once PATH_PRIVATE.'V/VArtikel.php';

class VArtikel_Test extends Testcase {
  
  public function test_01() {
    $v = new VArtikel();
    $this->assertNotNull($v);
  }
  
  
  /**
   * testing parseArtikel()
   */
  public function test_pa01() {
    // Text mit 1 Video und 1 Bild
    $text = 'huhu, Bildchen <imga id="12"> uuund'."\n"
      .'Video: <video id="15">'
    ;
    $bilder = array(1 => array(
      'id' => 12,
      'width' => 50,
      'height' => 40,
      'alt' => 'Beschreibung...',
      'url' => 'meine-url',
      'ext' => 'png'
    ));
    $vids = array(1 => array(
      'id' => 15,
      'width' => 100,
      'height' => 80,
      'vname' => 'pron',
      'sources' => 1
    ));
    $v = new VArtikel();
    $output = $v->parseArtikel($text, $bilder, $vids);
    $soll = 'huhu, Bildchen <div align="center">'."\n"
      .'<img src="'.BASEURL.'imga/meine-url.png" width="50" height="40" alt="Beschreibung...">'."\n"
      .'</div>'."\n"
      .' uuund'."\n"
      .'<br>'."\n"
      .'Video: <video width="100" height="80" controls>'."\n"
      .' <source src="'.BASEURL.'imga/pron.mp4" type="video/mp4">'."\n"
      .' Ihr Browser unterstützt den Tag "video" nicht.'."\n"
      .'</video>'."\n"
    ;
    $this->assertSame($soll, $output);
  }
  
}

