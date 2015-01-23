<?
class MLeser {
  
  public function get_all() {
    $p = new Page();
    $dbh = $p->get_db();
    $stmt = $dbh->prepare("SELECT * FROM leser");
    if (!$stmt->execute()) {
      throw new Exception('Fehler beim Holen der Abonnements');
    }
    $lines = array();
    while ($row = $stmt->fetch()) {
      array_push($lines, $row);
    }
    
    return $lines;
  }
  
  public function delete($lid) {
    $p = new Page();
    $dbh = $p->get_db();
    $stmt = $dbh->prepare("DELETE FROM leser WHERE id=:lid");
    $stmt->bindParam(':lid', $lid );
    if (!$stmt->execute()) {
      throw new Exception('Fehler beim Löschen eines Lesers');
    }
    
    return true;
  }    
  
}
