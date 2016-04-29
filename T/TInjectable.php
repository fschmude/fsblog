<?
trait TInjectable {

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
   * @param string $key (class name)
   * @return object - either the injected object, the freshly created object otherwise
   */
  protected function getObject($key) {
    if (!isset($this->objs[$key])) {
      // Object was not injected, create it now
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

