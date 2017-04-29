<?
/**
 * Mutter aller Controller
 */
require_once PATH_PRIVATE.'config.php';
require_once PATH_PRIVATE.'C/IController.php';
require_once PATH_PRIVATE.'T/TInjectable.php';

abstract class Controller implements IController {
  
  use TInjectable;

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
   * Destruktor: Schließt DB-Verbindung, falls eine aufgebaut wurde
   */
  public function __destruct() {
    if (isset($GLOBALS['pdo']) && $GLOBALS['pdo']) {
      $GLOBALS['pdo'] = null;
    }
  }
  
}
