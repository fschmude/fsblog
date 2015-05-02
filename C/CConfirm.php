<?
require_once 'config.php';
require_once 'M/Email.php';
require_once 'M/MLeser.php';
class CConfirm {
  
  public function work($get, $post, $mart, $mleser, $view) {
    $errmsg = $msg = '';
    $misc = array();
    $navi_arts = array();
    
    try {
      $b_input_wellformed = false;
      $titel = '';
      $displaymode = VCONFIRM_DISPLAYMODE_NOTHING;
      $Mail = new Email();
      
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
        } elseif (!$Mail->validate_address($usermail)) {
          $msg = '"'.$usermail.'" scheint keine gültige E-Mail-Adresse zu sein.';
        } elseif (!strlen($ptext = trim($post['ptext']))) {
          $msg = 'Es wurde kein Posting eingetippt.';
        } else {
          // ok, do something
          if ($mart->create_post($aid, trim($post['username']), $usermail, $ptext)) {
            $displaymode = VCONFIRM_DISPLAYMODE_POSTMAIL;
          }
        }
        $misc = array(
          'aurl' => $mart->getUrl($aid),
          'usermail' => $usermail
        );
      }
      
      // confirm post?
      if (isset($get['pid']) && ($pid = (int) $get['pid']) && isset($get['code'])) {
        $b_input_wellformed = true;
        $titel = 'Bestätigung Mail-Adresse';
        if (!strlen($code = trim($get['code']))) {
          $msg = 'Es wurde kein Bestätigungs-Code angegeben.';
        } else {
          $pinfo = $mart->confirm_post($pid, $code);
          $displaymode = VCONFIRM_DISPLAYMODE_POSTCONFIRM;
          $misc = array(
            'url' => $mart->getUrl($pinfo['aid']),
            'usermail' => $pinfo['usermail']
          );
        }
      }
      
      // lesermail entered?
      if (isset($post['lmail'])) {
        $b_input_wellformed = true;
        $titel = 'Erfassung Mail-Adresse';
        $lmail = trim($post['lmail']);
        if (!$lmail) {
          $msg = 'Keine E-Mail-Adresse übergeben';
        } elseif (!$Mail->validate_address($lmail)) {
          $msg = '"'.$lmail.'" ist keine gültige E-Mail-Adresse.';
        } else {
          $mleser->create_leser($lmail);
          $displaymode = VCONFIRM_DISPLAYMODE_LMAIL;
          $misc = array('lmail' => $lmail);
        } 
      }
      
      // lesermail confirmed?
      if (isset($get['lmc']) && ($code = trim($get['lmc']))) {
        $b_input_wellformed = true;
        $titel = 'Mailadresse bestätigt';
        $lmail = $mleser->confirm($code);
        $displaymode = VCONFIRM_DISPLAYMODE_LCONFIRM;
        $misc = array('lmail' => $lmail);
      }
      
      if (!$b_input_wellformed) {
        throw new Exception('Input-Variablen stimmen nicht...');
      }
      
      // navi_arts werden in jedem Fall gebraucht
      $navi_arts = $mart->get_top(3);
      
    } catch (Exception $e) {
      $errmsg = $e->getMessage();
    }

    // now display the whole thing
    $view->display($errmsg, $msg, $titel, $navi_arts, $displaymode, $misc);
  }
  
}
