<?
/**
 * Controller für alle Leser-Interaktionen 
 */
require_once 'C/Controller.php';

define('CONFIRM_DISPLAYMODE_NOTHING', 0);
define('CONFIRM_DISPLAYMODE_POSTMAIL', 1);
define('CONFIRM_DISPLAYMODE_POSTCONFIRM', 2);
define('CONFIRM_DISPLAYMODE_LMAIL', 3);
define('CONFIRM_DISPLAYMODE_LCONFIRM', 4);

class CConfirm extends Controller {
  
  public function work($get, $post, $files) {
    $errmsg = $msg = $lmail = $usermail = $aurl = '';
    $navi_arts = array();
    
    try {
      $b_input_wellformed = false;
      $titel = '';
      $displaymode = CONFIRM_DISPLAYMODE_NOTHING;
      $Mail = $this->getObject('MEmail');
      $mart = $this->getObject('MArtikel');
      $mpost = $this->getObject('MPost');
      $mleser = $this->getObject('MLeser');
      
      // new post?
      if (isset($post['aid']) && ($aid = (int) $post['aid'])
        && isset($post['username'])
        && isset($post['usermail'])
        && isset($post['ptext'])
      ) {
        $b_input_wellformed = true;
        $titel = 'Kommentar erfasst';
        if (!strlen($usermail = trim($post['usermail']))) {
          $msg = 'Keine E-Mail-Adresse angegeben.';
        } elseif (!$Mail->validateAddress($usermail)) {
          $msg = '"'.$usermail.'" scheint keine gültige E-Mail-Adresse zu sein.';
        } elseif (!strlen($ptext = trim($post['ptext']))) {
          $msg = 'Es wurde kein Posting eingetippt.';
        } else {
          // ok, do something
          if ($mpost->createPost($aid, trim($post['username']), $usermail, $ptext)) {
            $displaymode = CONFIRM_DISPLAYMODE_POSTMAIL;
          }
        }
        $aurl = $mart->getUrl($aid);
      }
      
      // confirm post?
      if (isset($get['pid']) && ($pid = (int) $get['pid']) && isset($get['code'])) {
        $b_input_wellformed = true;
        $titel = 'Bestätigung Mail-Adresse';
        if (!strlen($code = trim($get['code']))) {
          $msg = 'Es wurde kein Bestätigungs-Code angegeben.';
        } else {
          $pinfo = $mpost->confirmPost($pid, $code);
          $displaymode = CONFIRM_DISPLAYMODE_POSTCONFIRM;
          $aurl = $mart->getUrl($pinfo['aid']);
          $usermail = $pinfo['usermail'];
        }
      }
      
      // lesermail entered?
      if (isset($post['lmail'])) {
        $b_input_wellformed = true;
        $titel = 'Erfassung Mail-Adresse';
        $lmail = trim($post['lmail']);
        if (!$lmail) {
          $msg = 'Keine E-Mail-Adresse übergeben';
        } elseif (!$Mail->validateAddress($lmail)) {
          $msg = '"'.$lmail.'" ist keine gültige E-Mail-Adresse.';
        } else {
          $mleser->createLeser($lmail);
          $displaymode = CONFIRM_DISPLAYMODE_LMAIL;
        } 
      }
      
      // lesermail confirmed?
      if (isset($get['lmc']) && ($code = trim($get['lmc']))) {
        $b_input_wellformed = true;
        $titel = 'Mailadresse bestätigt';
        $lmail = $mleser->confirm($code);
        $displaymode = CONFIRM_DISPLAYMODE_LCONFIRM;
      }
      
      if (!$b_input_wellformed) {
        throw new Exception('Input-Variablen stimmen nicht...');
      }
      
      // navi_arts werden in jedem Fall gebraucht
      $navi_arts = $mart->getTop(3);
      
    } catch (Exception $e) {
      $errmsg = $e->getMessage();
    }

    // now display the whole thing
    $view = $this->getObject('VConfirm');
    $view->display($errmsg, array(
      'msg' => $msg,
      'titel' => $titel,
      'navi_arts' => $navi_arts,
      'displaymode' => $displaymode,
      'usermail' => $usermail,
      'lmail' => $lmail,
      'aurl' => $aurl
    ));
  }
  
}

