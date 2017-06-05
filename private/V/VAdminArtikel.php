<?
/**
 * edit form 1 article
 */
require_once PATH_PRIVATE.'V/VAdmin.php';

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
  public function display($errmsg, $artikel) {
    $this->displayHead($errmsg, 'Artikel editieren');

    echo ' -&gt; ';
    $this->displayNaviLink('Bild_list', 'Bilder verwalten');
    echo ' &nbsp; -&gt; ';
    $this->displayNaviLink('Video_list', 'Videos verwalten');
    echo '<br><br>'."\n";
    ?>
    aid = <?= $artikel['id'] ?>
    <form method="post" action="admin.php">
    <input type="hidden" name="mode" value="Artikel_up">
    <input type="hidden" name="id" value="<?= $artikel['id'] ?>">
    Datum (YYYY-MM-DD,hh:mm):
    <input type="text" name="datum" value="<?= $artikel['datum'] ?>" style="width:150px">
    <br>
    Status:
    <select name="status">
    <option value="0" <?= $artikel['status'] ? '' : 'selected' ?>>unsichtbar</option>
    <option value="1" <?= $artikel['status'] ? 'selected' : '' ?>>sichtbar</option>
    </select>
    <br>
    Titel (max 70, mit FS 66, das was ranken soll):
    <br>
    <input type="text" name="titel" value="<?= $artikel['titel'] ?>" style="width:600px">
    (<?= strlen($artikel['titel']) ?>)
    <br>
    URL:
    <br>
    <input type="text" name="url" value="<?= $artikel['url'] ?>" style="width:600px">
    <br>
    Description (max 156):
    <br>
    <input type="text" name="metadesc" value="<?= $artikel['metadesc'] ?>" maxlen="140" style="width:600px">
    (<?= strlen($artikel['metadesc']) ?>)
    <br>
    Text des Artikels:
    <br>
    <table width="100%">
    <tr>
    <td rowspan="2">
      <textarea type="text" name="text" id="text" rows="15" style="width:100%"><?= $artikel['text'] ?></textarea>
    </td>
    <td>
      <?
      foreach ($artikel['lastImgas'] as $imga) {
        ?>
        <a href="javascript:writeImga(<?= $imga['id'] ?>);">
        <img src="imga/<?= $imga['id'] ?>.<?= $imga['ext'] ?>" style="width:<?= $imga['width'] ?>px; height:<?= $imga['height'] ?>px;">
        </a>
        <?
      }
      ?>
    </td>
    </tr>
    <tr>
    <td style="width:150px; text-align:center; vertical-align:bottom;">
      <button onclick="javascript:writeAhref();" value="a">a-href</button>
    </td>
    </tr>
    </table>
    <?
    $this->displayEditHint();
    ?>
    <br>
    
    <div align="center">
    <input type="submit" value="Eintragen">
    </div>
    </form>
    
    <?
    if ($artikel['url']) {
      ?>
      <a href="<?= $artikel['completeUrl'] ?>" target="_blank">Preview</a>
      <?
    } else {
      echo 'Kein Preview-Link, da noch kein URL vergeben.';
    }
    echo '<br>'."\n";

    $this->displayFoot();
  }

}
