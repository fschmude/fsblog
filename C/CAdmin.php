<?
require_once 'C/Controller.php';

class CAdmin extends Controller {
  
  public function work($get, $post, $files) {
    $data = '';
    $errmsg = '';
    try {
      $v = $this->getObject('VAdminStart');
      
      // Anmeldung überprüfen!
      if (isset($get['action']) && $get['action'] == 'logout') {
        $_SESSION['ok'] = false;
        $data = array('msg' => 'Sie sind nun abgemeldet. Bitte Passwort eingeben.');
      } elseif (!isset($_SESSION['ok']) || !$_SESSION['ok']) {
        $pass = isset($post['pass']) ? $post['pass'] : '';
        if (!$pass) {
          $data = array('msg' => 'Bitte Passwort eingeben.');
        } else {
          
          // last login try?
          $strLastLogin = file_get_contents(LOGIN_FILE);
          if (!$strLastLogin) {
            throw new Exception('Letzter Login-Versuch konnte nicht gelesen werden.');
          }
          $dt = new DateTime($strLastLogin);
          $dt->add(new DateInterval('PT'.LOGIN_REFRAK.'S'));
          $dtnow = new DateTime();
          $f = fopen(LOGIN_FILE, 'w');
          fwrite($f, $dtnow->format('Y-m-d H:i:s'));
          fclose($f);
          if ($dtnow < $dt) {
            $data = array('msg' => 'Bitte nur ein Login-Versuch alle '.LOGIN_REFRAK.' Sekunden.');
          } elseif ($pass != BACKEND_PASSWORD) {
            $data = array('msg' => 'Falsches Passwort.');
          } else {
            $_SESSION['ok'] = true;
          }
        }
      }
      
      if (!isset($_SESSION['ok']) || !$_SESSION['ok']) {
        // v ist schon AdminStart
        
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
            $model->edit($post, isset($files['datei']) ? $files['datei'] : array());
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
      $errmsg = $this->handleError($e);
      $v = $this->getObject('VAdminStart');
    }
    
    // Now display our findings
    $v->display($errmsg, $data);
  }

}

