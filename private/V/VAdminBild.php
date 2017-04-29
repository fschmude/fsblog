<?
/**
 * edit form 1 article
 */
require_once PATH_PRIVATE.'V/VAdmin.php';

class VAdminBild extends VAdmin {
  
  /**
   * display the edit form for a single image
   * @param string $errmsg
   * @param array $data = array(
   *  );
   */
  public function display($errmsg, $bild) {
    $this->displayHead($errmsg, 'Bild editieren');

    $this->displayNaviLink('Bild_list', 'Bilder verwalten');
    echo '<br>'."\n";
    ?>
    
    Bild id = <?= $bild['id'] ?>
    <br>
    Aktuelles Bild (hier evtl. verkleinert):
    <br>
    <img width="<?= $bild['t_width'] ?>" height="<?= $bild['t_height'] ?>" src="<?= BASEURL ?>imga/<?= $bild['url'] ?>.<?= $bild['ext'] ?>">
    <br>

    <form method="post" action="admin.php" enctype="multipart/form-data">
    Bilddatei uploaden, bzw. ändern:
    <input type="file" name="datei" value="" style="width:300px">
    <hr>
    
    Möglichst nicht editieren (wird aus upload berechnet):
    <br>
    <input type="hidden" name="mode" value="Bild_up">
    <input type="hidden" name="id" value="<?= $bild['id'] ?>">
    Breite:
    <input type="text" name="width" value="<?= $bild['width'] ?>" style="width:100px">
    <br>
    Höhe:
    <input type="text" name="height" value="<?= $bild['height'] ?>" style="width:100px">
    <br>
    Extension (Dateinamen-Endung):
    <input type="text" name="ext" value="<?= $bild['ext'] ?>" style="width:100px">
    
    <hr>

    Ab hier editieren:
    <br>
    URL (ohne Endung):
    <input type="text" name="url" value="<?= $bild['url'] ?>" style="width:200px">
    <br>
    Beschreibung (für alt-Attribut):
    <input type="text" name="alt" value="<?= $bild['alt'] ?>" style="width:200px">
    <br>
    
    <input type="submit" value="Bilddaten ändern">
    </form>

    <?
    $this->displayFoot();
  }

}

