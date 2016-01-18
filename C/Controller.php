<?
/**
 * Mutter aller Controller
 */
require_once 'config.php';
require_once 'C/IController.php';

abstract class Controller implements IController {
  
  protected $objs;
 
  /**
   * Constructor
   * @var array $objs = optional array of injected objects
   */
  public function __construct($objs = array()) {
    $this->objs = $objs;
  }
  
  
  /**
   * Get an object of a given class
   * @param object $e Error-Object
   * @return string safe, displayable message
   */
  protected function handleError($e) {
    // in jedem Fall loggen
    $errmsg = $e->getMessage();
    $abschnitt = '--'."\n"
      .date('Y-m-d H:i:s').': '.$e->getFile().'('.$e->getLine().'):'."\n"
      .$errmsg."\n"
      .$e->getTraceAsString()."\n"
      .'--'."\n"
    ;
    $f = file_put_contents(LOG_FILE, $abschnitt, FILE_APPEND);
    
    // return only safe, displayable string
    if (DISPLAY_ERRORS) {
      return $errmsg;
    } else {
      return 'Fehler, siehe Logfile.';
    }
  }
  
  
  /**
   * Get an object of a given class
   * @param string $key (class name)
   * @return object - either the injected object, the freshly created object otherwise
   */
  protected function getObject($key) {
    if (!isset($this->objs[$key])) {
      // standard case: Object was not injected, create it now
      $letter = substr($key, 0, 1);
      $file = $letter.'/'.$key.'.php';
      if (!file_exists($file)) {
        throw new Exception('Datei "'.$file.'" nicht gefunden.');
      }
      require_once $file;
      $this->objs[$key] = new $key();
    }
    
    return $this->objs[$key];
  }
  
}

