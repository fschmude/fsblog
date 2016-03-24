<?
/**
 * View of all articles
 */
require_once 'V/VAdmin.php';

class VAdminBildList extends VAdmin {
  
  public function display($errmsg, $data) {
    $this->displayHead($errmsg, 'Artikelübersicht');

    $this->displayNaviLink('Bild_new', 'Neues Bild anlegen');

    // Liste anzeigen
    if (!count($data['rows'])) {
      echo 'Es konnten keine Bilder gefunden werden.';
    
    } else {
      ?>
      <table class="tded">
      <tr>
      <td class="tded">ID</td>
      <td class="tded">width</td>
      <td class="tded">height</td>
      <td class="tded">url</td>
      <td class="tded">ext</td>
      <td class="tded">alt</td>
      <td class="tded">edit</td>
      <td class="tded">delete</td>
      </tr>
      <?
      foreach ($data['rows'] as $bild) {
        ?>
        <tr>
        <td class="tded"><?= $bild['id'] ?></td>
        <td class="tded"><?= $bild['width'] ?></td>
        <td class="tded"><?= $bild['height'] ?></td>
        <td class="tded"><?= $bild['url'] ?></td>
        <td class="tded"><?= $bild['ext'] ?></td>
        <td class="tded"><?= $bild['alt'] ?></td>
        <td class="tded" style="text-align:center;">
          <a href="javascript:launchEdit(<?= $bild['id'] ?>);"><img src="img/icon_edit.png" width="16" height="16"></a>
        </td>
        <td class="tded" style="text-align:center;">
          <a href="javascript:launchDel(<?= $bild['id'] ?>,'Wollen Sie das Bild mit ID=<?= $bild['id'] ?> wirklich löschen?');"><img src="img/icon_delete.png" width="16" height="16"></a>
        </td>
        </tr>
        <?
      }
      ?>
      </table>
      
      <?
      $this->displayLinkForm('Edit', 'Bild_up1', true);
      $this->displayLinkForm('Del', 'Bild_del', true);
    }
    
    $this->displayFoot();
  }

}

