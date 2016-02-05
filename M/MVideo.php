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

