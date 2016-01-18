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
    $row = $this->dobj->getRow($id);
    
    // für thumbnail-Ausgabe
    $row['t_width'] = $row['width'];
    $row['t_height'] = $row['height'];
    if ($row['width'] > 100 || $row['height'] > 100) {
      if ($row['width'] > $row['height']) {
        $shrink = 100 / $row['width'];
      } else {
        $shrink = 100 / $row['height'];
      }
      $row['t_width'] = (int) ($row['width'] * $shrink);
      $row['t_height'] = (int) ($row['height'] * $shrink);
    }
    
    return $row;
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
    // Bild analysieren und speichern, falls upgeloadet
    if ((int) $upfile['size']) {
      if ((int) $upfile['error']) {
        throw new Exception('Fehler No. '.$upfile['error'].' beim Bild-Upload');
      }
      if ($type = trim($upfile['type'])) {
        $aType = explode('/', $type);
        $ext = $aType[1];
        if ($ext == 'jpeg') {
          $ext = 'jpg';
        }
        if (!in_array($ext, array('gif', 'jpg', 'png'))) {
          throw new Exception('"'.$ext.'" ist ein ungültiger Dateityp');
        }
        $row['ext'] = $ext;
      }
      
      // width, height ermitteln
      $isize = getimagesize($upfile['tmp_name']);
      $row['width'] = $isize[0]; 
      $row['height'] = $isize[1]; 

      // save file
      move_uploaded_file($upfile['tmp_name'], 'imga/'.$row['id'].'.'.$row['ext']);
    }
    
    // url nicht leer lassen
    if (!$url = trim($row['url'])) {
      $url = $row['id'];
    }
    $row['url'] = $url;
      
    // edit record
    $this->dobj->edit($row);

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

