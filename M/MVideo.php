<?
require_once 'D/DVideos.php';
require_once 'M/Model.php';

class MVideo extends Model {
  
  private $dobj = null;
  
  /**
   * Konstruktor
   */
  public function __construct() {
    $this->dobj = new DVideos;
  }
  

  /**
   * Ganze Tabelle liefern
   */
  public function getList() {
    $rows = $this->dobj->getAll();
    return array('rows' => $rows);
  }
  

  /**
   * Video-Information für eine ID liefern
   */
  public function getItem($vid) {
    $row =  $this->dobj->getRow($vid);
    $row['t_width'] = 400;
    $row['t_height'] = 300;
    return $row;
  }
  

  /**
   * 1 Video mit Metadaten editieren
   */
  public function edit($row, $file) {
    if ((int) $file['size']) {
      if ((int) $file['error']) {
        throw new Exception('Fehler No. '.$file['error'].' beim Video-Upload');
      }
      // save file
      move_uploaded_file($file['tmp_name'], 'imga/'.$row['vname'].'.mp4');
    }
    
    // edit record
    $this->dobj->edit($row);

    return true;
  }
  

  /**
   * Neuer DS
   */
  public function create() {
    return $this->dobj->create();
  }
  

  /**
   * Löschen
   */
  public function delete($vid) {
    $this->dobj->delete($vid);
  }
  

  /**
   * Video-Information für eine ID liefern
   */
  public function getInfo($vid) {
    // check
    if (!$vid = (int) $vid) {
      throw new Exception('Keine Video-ID gegeben ('.$vid.')');
    }
    
    // go
    $row = $this->dobj->getRow($vid);
    $sources = 0;
    if (file_exists('imga/'.$row['vname'].'.mp4')) {
      $sources += 1;
    }
    if (file_exists('imga/'.$row['vname'].'.ogg')) {
      $sources += 2;
    }
    $row['sources'] = $sources;
    return $row;
  }
    
}

