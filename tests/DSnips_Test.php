<?
require_once 'Testcase.php';
require_once 'D/DSnips.php';

class DSnips_Test extends Testcase {
  
  public function test_01() {
    $m = new DSnips();
    $this->assertNotNull($m);
  }
  
  
  /*
   * tests for getBefore, getAfter
   */
  public function test_gb1() {
    // error, falls sid ungültig
    $d = new DSnips();
    try {
      $d->getBefore(0);
      $thrown = false;
    } catch (Exception $e) {
      $this->assertSame('Keine gültige sid angegeben', $e->getMessage());
      $thrown = true;
    }
    $this->assertTrue($thrown, 'Error nicht ausgelöst');
  }
  
  public function test_gb2() {
    // all ok => letztes Datum muss kommen, nicht letzte ID
    $d = new DSnips();
    $this->execSqls(array(
      "DELETE FROM snips WHERE id<=3",
      "INSERT INTO snips(id, datum, text) VALUES(1, '1969-03-29', 'text 1')",
      "INSERT INTO snips(id, datum, text) VALUES(2, '1969-03-28', 'text 2')",
      "INSERT INTO snips(id, datum, text) VALUES(3, '1969-03-30', 'text 3')",
    ));
    $erg = $d->getBefore(3);
    $this->assertSame('1', $erg);
  }
  
  public function test_ga1() {
    // getAfter all ok => nächstes Datum muss kommen, nicht nächste ID
    $d = new DSnips();
    $this->execSqls(array(
      "DELETE FROM snips WHERE id<=3",
      "INSERT INTO snips(id, datum, text) VALUES(1, '1969-03-29', 'text 1')",
      "INSERT INTO snips(id, datum, text) VALUES(2, '1969-03-31', 'text 2')",
      "INSERT INTO snips(id, datum, text) VALUES(3, '1969-03-30', 'text 3')",
    ));
    $erg = $d->getAfter(1);
    $this->assertSame('3', $erg);
  }
  
  
  /*
   * getAllIds()
   */
  public function test_gai1() {
    $stmt = $this->pdo->prepare(
      "SELECT count(*) anz FROM snips"
    );
    if (!$stmt->execute()) {
      $this->fail('Fehler beim Zählen aller Schnippel');
    }
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    $anz = $res['anz'];
    
    $m = new DSnips();
    $erg = $m->getAllIds();
    
    $this->assertSame((int) $anz, count($erg));
  }
  
}

