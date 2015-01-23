<?
/**
 * Handle emails
 */
define('EMAIL_ADMIN', 'mail@fritz-schmude.de');
define('EMAIL_OFFIZIELL', 'mail@fs-blog.de');

class Email {
  
  /**
   * Constructor
   */
  public function __construct() {
  }

  // mail verschicken
  public function mailen( $adr, $betreff, $text ) {
    if (ORT == "live") {
      $header = 'From: '.EMAIL_OFFIZIELL."\n"
        .'MIME-Version: 1.0'."\n"
        .'Content-type: text/plain; charset=utf-8'."\n"
        .'Content-Transfer-Encoding: 8bit'
      ;
      mail($adr, $betreff, $text, $header);
      
    } else {
      echo '<div style="background-color:#ccffff;padding:5px;border:1px solid #009999;">';
      echo 'ORT != "live" ("'.ORT.'"), daher kein echtes Mail.<br>';
      echo 'Folgendes würde verschickt - an '.$adr.'<br><br>';
      echo 'Betreff: '.$betreff."<br><br>";
      echo str_replace("\n", "\n<br>\n", $text);
      echo '</div><br>';
    }
  }
  
  // Adresse validieren
  public function validate_address($eadr) {
    $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.([a-z]{2,4}|museum))$/i';
    $b_ok = preg_match($regex, $eadr);
    return $b_ok;
  }

}
