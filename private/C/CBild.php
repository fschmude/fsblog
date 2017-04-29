<?
/**
 * Controller für Bilder-Auslieferung
 */
require_once PATH_PRIVATE.'C/Controller.php';

class CBild extends Controller {
  
  public function work($get, $post, $files) {
    try {
      // get data
      $iurl = isset($get['iurl']) ? $get['iurl'] : '';
      if (!$iurl) {
        throw new Exception('Keine iurl angegeben');
      }
      $m = $this->getObject('MBild');
      $data = $m->getImageInfo($iurl);
      
      // check file
      $file = $m->getPath($data['id'], $data['ext']);
      if (!file_exists($file)) {
        throw new Exception($file.' doesnt exist');
      }
      
      // output whatever we've found
      header('Content-type: image/'.$data['ext']); 
      readfile($file);
      
    } catch (Exception $e) {
      $errmsg = $this->handleError($e);
      echo $errmsg;
    }
  }

}

