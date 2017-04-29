<?
// Pfad vom Startpunkt der Tests aus nach /private, muss schon während der include-Phase laufen
define('PATH_PRIVATE', 'private/');

require_once PATH_PRIVATE.'D/DArtikel.php';

class Testcase extends PHPUnit_Framework_TestCase {

  protected $pdo;
  
  protected function setUp() {
    parent::setUp();
    if (!DISPLAY_ERRORS) {
      $this->fail('Für die Tests muss DISPLAY_ERRORS in '.PATH_PRIVATE.'config.php true sein');
    }
    $db = new DArtikel();
    $this->pdo = $db->getPdo();
  }
  
  protected function execSqls($sqls) {
    if (is_array($sqls)) {
      foreach ($sqls as $sql) {
        $stmt = $this->pdo->prepare($sql);
        if (!$stmt->execute()) {
          echo 'Fehler bei "'.$sql.'"!';
        }
      }
    }
  }

  protected function checkDb($sql, $asserts) {
    $stmt = $this->pdo->prepare($sql);
    if (!$stmt->execute()) {
      echo 'Fehler bei "'.$sql.'"!';
    }
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!is_array($row)) {
      echo 'Kein Output von "'.$sql.'"!';
      return;
    }
    foreach ($asserts as $key => $val) {
      $this->assertSame($row[$key], $val);
    }
  }

  protected function tearDown() {
    parent::tearDown();
  }
  
}
