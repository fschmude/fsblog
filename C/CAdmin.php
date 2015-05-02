<?
class CAdmin {
  
  protected $objs;
  
  /**
   * Resolve dependency injection
   * @param array $objects (for testing)
   * @param string $key
   * @return object - either the injected object, the default object otherwise
   */
  protected function getObject($key) {
    if (!isset($this->objs[$key])) {
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


  public function run($get, $post) {
    $data = '';
    $errmsg = '';
    try {
      // Anmeldung überprüfen!
      if (isset($get['action']) && $get['action'] == 'logout') {
        $_SESSION['ok'] = false;
        $data = array('msg' => 'Sie sind nun abgemeldet. Bitte Passwort eingeben.');
        $v = $this->getObject('VAdminStart');
      
      } elseif (!isset($_SESSION['ok']) || !$_SESSION['ok']) {
        $pass = isset($post['pass']) ? $post['pass'] : '';
        if (!$pass) {
          $data = array('msg' => 'Bitte Passwort eingeben.');
          $v = $this->getObject('VAdminStart');
        } elseif ($pass != BACKEND_PASSWORD) {
          $data = array('msg' => 'Falsches Passwort.');
          $v = $this->getObject('VAdminStart');
        } else {
          $_SESSION['ok'] = true;
        }
      }
      
      if (!isset($_SESSION['ok']) || !$_SESSION['ok']) {
        $v = $this->getObject('VAdminStart');
        
      } else {
        if (!isset($post['mode']) || !$mode = $post['mode']) {
          $mode = 'Artikel_list';
        }
        
        $aAction = explode('_', $mode);
        if (count($aAction) == 2) {
          $objtype = $aAction[0];
          $act = $aAction[1];
          $mclass = 'M'.$objtype;
          $model = $this->getObject($mclass);
          $vclass = 'VAdmin'.$objtype;
          switch ($act) {
          case 'del':
            $model->delete($post['id']);
            // fallthrough
          case 'list':
            $data = $model->getList();
            $v = $this->getObject($vclass.'List');
            break;
            
          case 'new':
            $id = $model->create();
            $data = $model->getItem($id);
            $v = $this->getObject($vclass);
            break;
            
          case 'up':
            $model->edit($post);
            // fallthrough
          case 'up1':
            $data = $model->getItem($post['id']);
            $v = $this->getObject($vclass);
            break;
            
          default:
            throw new Exception('Ungültiger mode: "'.$mode.'"');
          }
        }
      }
      
    } catch (Exception $e) {
      $errmsg = $e->getMessage();
      $data = 'Sonstiger Fehler';
      $v = $this->getObject('VAdminStart');
    }
    
    // Now display our findings
    $v->display($errmsg, $data);
  }


}
