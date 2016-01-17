<?
// muss schon während der include-Phase laufen
set_include_path(get_include_path().PATH_SEPARATOR.'/home/fs/vw/blog');

require_once 'D/DArtikel.php';

class Testcase extends PHPUnit_Framework_TestCase {

  protected $pdo;
  
  protected function setUp() {
    parent::setUp();
    $m = new DArtikel();
    $this->pdo = $m->getPdo();
  }
  
  protected function exec_sqls($sqls) {
    if (is_array($sqls)) {
      foreach ($sqls as $sql) {
        $stmt = $this->pdo->prepare($sql);
        if (!$stmt->execute()) {
          echo 'Fehler bei "'.$sql.'"!';
        }
      }
    }
  }

  protected function check_db($sql, $asserts) {
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
