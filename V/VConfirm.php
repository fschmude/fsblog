<?
require_once 'V/View.php';

class VConfirm extends View {
  
  /**
   * Zentrale Anzeigefunktion
   */
  public function display($errmsg, $vdata) {
    $content = '';
    switch ($vdata['displaymode']) {
    case CONFIRM_DISPLAYMODE_POSTMAIL:
      $content = 'Danke für Ihren Kommentar.'
        .'<br><br>'."\n"
        .'Sie erhalten in Kürze eine E-Mail an "'.$vdata['usermail'].'" mit einem Bestätigungslink.'
        .'<br><br>'."\n"
        .'Bitte sehen Sie in Ihr Postfach, um diesen Link zu aktivieren.'
        .'<br><br>'."\n"
        .'Danach wird Ihr Kommentar, sofern er nicht beleidigend und/oder rechtlich untragbar ist, freigeschaltet.'
        .'<br><br>'."\n"
      ;
      break;
      
    case CONFIRM_DISPLAYMODE_POSTCONFIRM:
      $content = 'Die von Ihnen angegebene E-Mail-Adresse "'.$vdata['usermail'].'" wurde bestätigt.'
        .'<br><br>'."\n"
        .'Sobald Ihr Kommentar inhaltlich geprüft ist, wird er freigeschaltet.'
        .'<br><br>'."\n"
      ;  
      break;
      
    case CONFIRM_DISPLAYMODE_LMAIL:
      $content = 'Die von Ihnen angegebene E-Mail-Adresse "'.$vdata['lmail'].'" wurde vorläufig eingetragen.'
        .'<br><br>'."\n"
        .'Sie erhalten in Kürze eine E-Mail.'
        .'<br><br>'."\n"
        .'Bitte sehen Sie in Ihr Postfach, um den darin enthaltenen Bestätigungs-Link anzuklicken.'
        .'<br><br>'."\n"
      ;
      break;
      
    case CONFIRM_DISPLAYMODE_LCONFIRM:
      $content = 'Ihre E-Mail-Adresse "'.$vdata['lmail'].'" wurde bestätigt.'
        .'<br><br>'."\n"
        .'Sie werden nun bei allen neuen Artikeln auf fs-blog.de benachrichtigt.  '
        .'<br><br>'."\n"
      ;
      break;
      
    case CONFIRM_DISPLAYMODE_NOTHING:
      break;
      
    default:
      $errmsg = 'Ungültiger display mode: '.$vdata['displaymode'];
    }
    
    $this->head($vdata['titel'], '', '', '');
    
    if ($errmsg) {
      $this->errmsg($errmsg);
      
    } else {
      if ($vdata['msg']) {
        echo $vdata['msg'];
        echo '<br><br>'."\n";
      }
      if ($content) {
        echo $content;
      }
      
      if (isset($vdata['aurl']) && $vdata['aurl']) {
        echo '<a href="'.$this->completeUrl($vdata['aurl']).'">Zurück zum Artikel</a>';
        echo '<br><br>';
      }
      ?>
      <a href="index.php">Zurück zum Start</a>
      <?
    }
    
    $this->foot($vdata['navi_arts']);
  }
  
}

