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
      "UPDATE bilder SET width=:width, height=:height, url=:url, ext=:ext, alt=:alt "
      ." WHERE id=:id"
    );
    if (!$stmt->execute(array(
        ':id' => $row['id'],
        ':width' => $row['width'],
        ':height' => $row['height'],
        ':url' => $row['url'],
        ':ext' => $row['ext'],
        ':alt' => $row['alt']
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
  

  /**
   * Helper: get full path from image id, when extension is already known
   * @param int $id
   * @param string $ext
   * @return array
   */
  public function getPath($id, $ext) {
    return BASEPATH.'/imga/'.$id.'.'.$ext;
  }


  /**
   * Get image info
   * @param string image url ohne Endung, e.g. "blaues-bild" oder "12"
   * @return array
   */
  public function getImageInfo($url) {
    // Zahl oder Text-Url
    if ((int) $url) {
      $sql = "SELECT * FROM bilder WHERE id=:id";
      $pms = array(':id' => $url);
    } else {
      $sql = "SELECT * FROM bilder WHERE url=:url";
      $pms = array(':url' => $url);
    }
    
    // now get row from db
    $stmt = $this->get_pdo()->prepare($sql);
    if (!$stmt->execute($pms)) {
      throw new Exception('Fehler beim Suchen nach '.$url);
    }
    $ret = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$ret) {
      throw new Exception('Kein Datensatz zu Bild-URL "'.$url.'"');
    }
    return $ret;
  }

}

