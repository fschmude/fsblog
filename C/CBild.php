<?
/**
 * Controller für Bilder-Auslieferung
 */
require_once 'C/CController.php';

class CBild extends CController {
  
  public function run($get, $post, $files) {
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
      $errmsg = $e->getMessage();
      $f = fopen(LOG_FILE, 'a');
      fwrite($f, date('Y-m-d H:i:s').': '.$e->getFile().'('.$e->getLine().'): '.$errmsg."\n");
      fwrite($f, $e->getTraceAsString()."\n");
      fclose($f);
      
      echo 'Error, see log file.';
    }
  }

}

