<?
/**
 * edit form 1 article
 */
require_once 'V/VAdmin.php';

class VAdminBild extends VAdmin {
  
  /**
   * display the edit form for a single image
   * @param string $errmsg
   * @param array $data = array(
   *  );
   */
  public function display($errmsg, $data) {
    $this->displayHead($errmsg, 'Bild editieren');
    ?>
    Bild id = <?= $data['id'] ?>
    <br>
    Möglichst nicht editieren:
    <form method="post" action="admin.php" enctype="multipart/form-data">
    <input type="hidden" name="mode" value="Bild_up">
    <input type="hidden" name="id" value="<?= $data['id'] ?>">
    Breite:
    <input type="text" name="width" value="<?= $data['width'] ?>" style="width:100px">
    <br>
    Höhe:
    <input type="text" name="height" value="<?= $data['height'] ?>" style="width:100px">
    <br>
    Extension (Dateinamen-Endung):
    <input type="text" name="ext" value="<?= $data['ext'] ?>" style="width:100px">
    <br>

    Ab hier editieren:
    <br>
    URL (ohne Endung):
    <input type="text" name="url" value="<?= $data['url'] ?>" style="width:200px">
    <br>
    Beschreibung (für alt-Attribut):
    <input type="text" name="alt" value="<?= $data['alt'] ?>" style="width:200px">
    <br>
    Bilddatei ändern, falls nötig:
    <input type="file" name="datei" value="" style="width:300px">
    <br>
    
    <input type="submit" value="Bilddaten ändern">
    
    </form>

    Aktuelles Bild (hier evtl. verkleinert):
    <br>
    <img width="<?= $data['t_width'] ?>" height="<?= $data['t_height'] ?>" src="<?= BASEURL ?>imga/<?= $data['url'] ?>.<?= $data['ext'] ?>">
    
    <form action="admin.php" method="post">
    <input type="hidden" value="Bild_list" name="mode"></input>
    <div align="center">
    <input type="submit" value="Bilder verwalten">
    </div>
    </form>

    <form action="admin.php" method="post">
    <input type="hidden" value="Artikel_list" name="mode"></input>
    <div align="center">
    <input type="submit" value="Artikel verwalten">
    </div>
    </form>
    
    <?
    $this->displayFoot();
  }

}

