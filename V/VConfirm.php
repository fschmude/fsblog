<?
define('VCONFIRM_DISPLAYMODE_NOTHING', 0);
define('VCONFIRM_DISPLAYMODE_POSTMAIL', 1);
define('VCONFIRM_DISPLAYMODE_POSTCONFIRM', 2);
define('VCONFIRM_DISPLAYMODE_LMAIL', 3);
define('VCONFIRM_DISPLAYMODE_LCONFIRM', 4);

require_once 'V/View.php';

class VConfirm extends View {
  
  /**
   * Zentrale Anzeigefunktion
   */
  public function display($errmsg, $msg, $titel, $navi_arts, $displaymode, $misc) {
    $content = '';
    switch ($displaymode) {
    case VCONFIRM_DISPLAYMODE_POSTMAIL:
      $content = 'Danke für Ihren Kommentar.'
        .'<br><br>'."\n"
        .'Sie erhalten in Kürze eine E-Mail an "'.$misc['usermail'].'" mit einem Bestätigungslink.'
        .'<br><br>'."\n"
        .'Bitte sehen Sie in Ihr Postfach, um diesen Link zu aktivieren.'
        .'<br><br>'."\n"
        .'Danach wird Ihr Kommentar, sofern er nicht beleidigend und/oder rechtlich untragbar ist, freigeschaltet.'
        .'<br><br>'."\n"
      ;
      break;
      
    case VCONFIRM_DISPLAYMODE_POSTCONFIRM:
      $content = 'Die von Ihnen angegebene E-Mail-Adresse "'.$misc['usermail'].'" wurde bestätigt.'
        .'<br><br>'."\n"
        .'Sobald Ihr Kommentar inhaltlich geprüft ist, wird er freigeschaltet.'
        .'<br><br>'."\n"
      ;  
      break;
      
    case VCONFIRM_DISPLAYMODE_LMAIL:
      $content = 'Die von Ihnen angegebene E-Mail-Adresse "'.$misc['lmail'].'" wurde vorläufig eingetragen.'
        .'<br><br>'."\n"
        .'Sie erhalten in Kürze eine E-Mail.'
        .'<br><br>'."\n"
        .'Bitte sehen Sie in Ihr Postfach, um den darin enthaltenen Bestätigungs-Link anzuklicken.'
        .'<br><br>'."\n"
      ;
      break;
      
    case VCONFIRM_DISPLAYMODE_LCONFIRM:
      $content = 'Ihre E-Mail-Adresse "'.$misc['lmail'].'" wurde bestätigt.'
        .'<br><br>'."\n"
        .'Sie werden nun bei allen neuen Artikeln auf fs-blog.de benachrichtigt.  '
        .'<br><br>'."\n"
      ;
      break;
      
    case VCONFIRM_DISPLAYMODE_NOTHING:
      break;
      
    default:
      $errmsg = 'Ungültiger display mode: '.$displaymode;
    }
    
    $this->head($titel, '', '', '', $navi_arts);
    
    if ($errmsg) {
      $this->errmsg($errmsg);
      
    } else {
      if ($msg) {
        echo $msg;
        echo '<br><br>'."\n";
      }
      if ($content) {
        echo $content;
      }
      
      if (isset($misc['aurl']) && $misc['aurl']) {
        echo '<a href="artikel/'.$misc['aurl'].'.htm">Zurück zum Artikel</a>';
        echo '<br><br>';
      }
      ?>
      <a href="index.php">Zurück zum Start</a>
      <?
    }
    
    $this->foot();
      
  }
  
}