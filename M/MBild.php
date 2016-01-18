<?
require_once 'D/DBilder.php';
require_once 'M/Model.php';

class MBild extends Model {
  
  private $dobj = null;
  
  /**
   * Konstruktor
   */
  public function __construct() {
    $this->dobj = new DBilder;
  }
  
  /**
   * Genau ein Bild anhand der ID holen
   */
  public function getItem($id) {
    return $this->dobj->getRow($id);
  }
  
  
  /**
   * Ein Bild-Datensatz erzeugen
   */
  public function create() {
    $id = $this->dobj->create();
    return $id;
  }
  
  
  /**
   * Ein Bild-Datensatz editieren
   */
  public function edit($row, $upfile) {
    // edit record
    $this->dobj->edit($row);

    // handle file
    if ($upfile['size']) {
      move_uploaded_file($upfile['tmp_name'], 'imga/'.$row['id'].'.'.$row['ext']);
    }
    return true;
  }
  
  
  /**
   * Ein Bild löschen
   */
  public function delete($id) {
    if (!$id = (int) $id) {
      throw new Exception('Keine Bild-ID gegeben');
    }
    $res = $this->getItem($id);
    $file = 'imga/'.$id.'.'.$res['ext'];
    
    // bild löschen, falls vorhanden
    if (file_exists($file)) {
      unlink($file);
    }
    
    // ds löschen
    $this->dobj->delete($id);
  }
  
  
  /**
   * Alle Bild-Datensätze holen
   */
  public function getList() {
    $res = $this->dobj->getAll();
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
    if (is_numeric($url)) {
      $ret = $this->dobj->getRow($url);
    } else {
      $ret = $this->dobj->getByUrl($url);
    }
    if (!$ret) {
      throw new Exception('Kein Datensatz zu Bild-URL "'.$url.'"');
    }
    return $ret;
  }

}

