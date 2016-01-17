<?
require_once 'D/DPosts.php';
require_once 'M/MArtikel.php';
require_once 'M/Model.php';
require_once 'M/MHelper.php';
require_once 'M/MEmail.php';

class MPost extends Model {
  
  private $dobj = null;
  
  /**
   * Konstruktor
   */
  public function __construct() {
    $this->dobj = new DPosts;
  }
  

  /**
   * posting erzeugen, noch nicht freigeschaltet!
   */
  public function createPost($aid, $username, $usermail, $ptext) {
    $dposts = new DPosts;
    // lfnr berechnen
    $oldLfnr = $dposts->getMaxLfnr($aid);
    $lfnr = (int) $oldLfnr + 1;
    
    // speichern
    $Helper = new MHelper();
    $code = $Helper->makeCode();
    $pid = $dposts->createValues(array(
      'aid' => $aid,
      'lfnr' => $lfnr,
      'code' => $code,
      'username' => $username,
      'usermail' => $usermail,
      'text' => $ptext,
      'datum' => date('Y-m-d H:i:s'),
      'status' => 0
    ));
    
    // mailen
    $mtext = 'Liebe/r '.$username.','."\n\n"
      .'Ihre E-Mail-Adresse ('.$usermail.') wurde auf fs-blog.de als Urheber des folgenden Kommentars angegeben:'."\n\n"
      .'"'.$ptext.'"'."\n\n"
      .'Wenn Sie wirklich wollen, dass dieser Beitrag veröffentlicht wird, klicken Sie bitte hier:'."\n"
      .BASEURL.'confirm.php?pid='.$pid.'&code='.$code."\n\n"
      .'(Wenn Ihr E-Mail-Programm diesen Link nicht klickbar anzeigt, kopieren Sie ihn bitte in das URL-Fenster eines Browsers.)'."\n\n"
      .'Wenn der Beitrag nicht von Ihnen stammt, so ignorieren Sie diese Mail bitte.'."\n\n"
      .'Mit freundlichen Grüßen'."\n\n"
      .'fs'."\n\n"
    ;
    
    $e = new MEmail();
    $e->mailen($usermail, 'Ihr Beitrag auf fs-blog.de', $mtext);
    return $pid;
  }


  /**
   * posting bestätigen
   */
  public function confirmPost($pid, $code) {
    $post = $this->dobj->getRow($pid);
    
    // check code
    if ($post['code'] != $code) {
      throw new Exception('Code ('.$code.') stimmt nicht überein.');
    }

    switch ($post['status']) {
    case 0:
      // ok, jetzt status setzen
      $this->dobj->setField($pid, 'status', 1);
      
      // an mich mailen
      $mtext = 'Bestätigung der E-Mail-Adresse von '.$post['username'].': '.$post['usermail']."\n\n"
        .'"'.$post['text'].'"'."\n\n"
        .'Beitrag freischalten:'."\n\n"
        .BASEURL.'admin.php'."\n\n"
      ;
      $e = new MEmail();
      $e->mailen(EMAIL_ADMIN, 'fs-blog.de: Freischaltung', $mtext);
      break;
      
    case 1:
      // schon freigeschaltet, vermutlich doppelt geklickt
      break;
      
    default:
      // verboten, gelöscht oder sonstwas
      throw new Exception('Dieser Beitrag darf nicht freigeschaltet werden.');
    }
    
    // return the whole posting row
    return $post;
  }
  
}

