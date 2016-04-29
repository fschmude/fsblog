<?
/**
 * edit form 1 video
 */
require_once 'V/VAdmin.php';

class VAdminVideo extends VAdmin {
  
  /**
   * display the edit form for a single video
   * @param string $errmsg
   * @param array $data = array(
   *  );
   */
  public function display($errmsg, $video) {
    $this->displayHead($errmsg, 'Video editieren');

    $this->displayNaviLink('Video_list', 'Videos Übersicht');
    echo '<br>'."\n";
    ?>
    
    Video id = <?= $video['id'] ?>
    <br>
    Aktuelles Video (400x300, nur mp4):
    <br>
    
    <form method="post" action="admin.php" enctype="multipart/form-data">
    <video width="<?= $video['t_width'] ?>" height="<?= $video['t_height'] ?>">
    <source src="<?= BASEURL ?>imga/<?= $video['vname'] ?>.mp4" type="video/mp4">
    </video>
    <br>
    Videodatei uploaden, bzw. ändern:
    <br>
    <input type="file" name="datei" value="" style="width:300px">
    <br>
    (Nur mp4, maximaler Upload = <?= ini_get('upload_max_filesize') ?>)
    <br>

    <input type="hidden" name="mode" value="Video_up">
    <input type="hidden" name="id" value="<?= $video['id'] ?>">
    <br>
    Breite:
    <input type="text" name="width" value="<?= $video['width'] ?>" style="width:100px">
    <br>
    Höhe:
    <input type="text" name="height" value="<?= $video['height'] ?>" style="width:100px">
    <br>
    Dateiname (ohne Endung):
    <input type="text" name="vname" value="<?= $video['vname'] ?>" style="width:200px">
    <br>
    <input type="submit" value="Videodaten ändern">
    </form>

    <?
    $this->displayFoot();
  }

}

