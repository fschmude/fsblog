<?
/**
 * edit form 1 article
 */
require_once 'V/VAdmin.php';

class VAdminArtikel extends VAdmin {
  
  /**
   * display the edit form for a single article
   * @param string $errmsg
   * @param array $data = array(
   *    'titel' => ...
   *    (fields of artikel)
   *    'pars' => array of paragraphs
   *    'authors' => array of author ids of this article
   *    'allauthors' => array of all authors with names
   *  );
   */
  public function display($errmsg, $data) {
    $this->displayHead($errmsg, 'Artikel editieren');
    ?>
    aid = <?= $data['id'] ?>
    <form method="post" action="admin.php">
    <input type="hidden" name="mode" value="Artikel_up">
    <input type="hidden" name="id" value="<?= $data['id'] ?>">
    Datum (YYYY-MM-DD,hh:mm):
    <input type="text" name="datum" value="<?= $data['datum'] ?>" style="width:150px">
    <br>
    Status:
    <input type="text" name="status" value="<?= $data['status'] ?>" style="width:20px">
    (0=unsichtbar, 1=sichtbar in navi)
    <br>
    Titel:
    <br>
    <input type="text" name="titel" value="<?= $data['titel'] ?>" style="width:600px">
    <br>
    URL:
    <br>
    <input type="text" name="url" value="<?= $data['url'] ?>" style="width:600px">
    <br>
    Description (max 140):
    <br>
    <input type="text" name="metadesc" value="<?= $data['metadesc'] ?>" maxlen="140" style="width:600px">
    <br>
    Text des Artikels:
    <br>
    <textarea type="text" name="text" rows="15" style="width:600px"><?= $data['text'] ?></textarea>
    <br>
    &lt;wiki href="wiki-Seitenname"&gt;verlinkter Text&lt;/wiki&gt;
    <br>
    &lt;imga id="bid"&gt;
    <br>
    &lt;h2&gt;Zwischenüberschrift&lt;/h2&gt; 
    <br>
    Als einziges sonst erlaubt: &lt;a...
    <br>
    <br>
    ACHTUNG!
    <br>
    "Bloggen dient m.E. hauptsaechlich eher der Selbstbeweihraeucherung des Autors,
    deswegen macht es ja Spass und jeder bloggt was das Zeug haelt.
    <br>
    An Fitel als Tip:
    Wenn die Ueberhoehung der eigenen Position und die Darstellung der eigenen intellektuellen geradezu uebermenschlichen Faehigkeiten zu extrem wird, 
    liest das Zeug keiner - 
    man laesst sich als Leser nur ungern in eine Idiotenecke stellen.
    <br>
    
    <div align="center">
    <input type="submit" value="Eintragen">
    </div>
    </form>
    
    <?
    if ($data['url']) {
      ?>
      <a href="<?= BASEURL.'artikel/'.$data['url'].'.htm' ?>" target="_blank">Preview</a>
      <?
    } else {
      echo 'Kein Preview-Link, da noch kein URL vergeben.';
    }

    ?>
    <div align="center">
    <form action="admin.php" method="post">
    <input type="hidden" value="Bild_list" name="mode"></input>
    <input type="submit" value="Bilder verwalten">
    </form>

    <form action="admin.php" method="post">
    <input type="hidden" value="Artikel_list" name="mode"></input>
    <input type="submit" value="Artikel verwalten">
    </form>

    </div>
    
    <?
    $this->displayFoot();
  }

}

