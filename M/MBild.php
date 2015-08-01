<?
require_once 'M/Model.php';

class MBild extends Model {
  
  /**
   * Genau ein Bild anhand der ID holen
   */
  public function getItem($id) {
    $stmt = $this->get_pdo()->prepare(
      "SELECT *"
      ." FROM bilder"
      ." WHERE id=:id"
    );
    if (!$stmt->execute(array(':id' => $id))) {
      throw new Exception('Fehler beim Suchen von Bild Nr. '.$id);
    }
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    return $res;
  }
  
  
  /**
   * Ein Bild-Datensatz editieren
   */
  public function create() {
    $sql = "INSERT INTO bilder VALUE()";
    $stmt = $this->get_pdo()->prepare($sql);
    if (!$stmt->execute()) {
      throw new Exception('Fehler bei '.$sql);
    }
    
    // get ID
    $id = $this->get_pdo()->lastInsertId();
    if (! (int) $id) {
      throw new Exception('Bild-id konnte nicht ermittelt werden');
    }
    return $id;
  }
  
  
  /**
   * Ein Bild-Datensatz editieren
   */
  public function edit($row, $files) {
    if (!isset($row['id']) || !(int)$row['id']) {
      throw new Exception('Ungültige ID beim Editieren eines Bildes');
    }
    
    $stmt = $this->get_pdo()->prepare(
      "UPDATE bilder SET width=:width, height=:height, ext=:ext "
      ." WHERE id=:id"
    );
    if (!$stmt->execute(array(
        ':id' => $row['id'],
        ':width' => $row['width'],
        ':height' => $row['height'],
        ':ext' => $row['ext']
    ))) {
      throw new Exception('Fehler beim Editieren des Bildes mit id='.$row['id']);
    }
    
    // handle file
    if ($files['datei']['size']) {
      move_uploaded_file($files['datei']['tmp_name'], 'imga/'.$row['id'].'.'.$row['ext']);
    }
    return true;
  }
  
  
  /**
   * Ein Bild löschen
   */
  public function delete($id) {
    $res = $this->getItem($id);
    $file = 'imga/'.$id.'.'.$res['ext'];
    
    // bild löschen, falls vorhanden
    if (file_exists($file)) {
      unlink($file);
    }
    
    // ds löschen
    $stmt = $this->get_pdo()->prepare(
      "DELETE FROM bilder WHERE id=:id"
    );
    if (!$stmt->execute(array(':id' => $id))) {
      throw new Exception('Fehler beim Löschen des Bildes Nr. '.$id);
    }
  }
  
  
  /**
   * Alle Bild-Datensätze holen
   */
  public function getList() {
    $stmt = $this->get_pdo()->prepare(
      "SELECT *"
      ." FROM bilder"
      ." ORDER BY id"
    );
    if (!$stmt->execute()) {
      throw new Exception('Fehler beim Holen aller Bilder');
    }
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $res;
  }
  

  
}
