<?
/**
 * View of all articles
 */
require_once 'V/VAdmin.php';

class VAdminBildList extends VAdmin {
  
  public function display($errmsg, $data) {
    $this->displayHead($errmsg, 'Artikelübersicht');

    ?>
    <form method="post" action="admin.php">
    <input type="hidden" name="mode" value="Artikel_new">
    <button type="submit">Neues Bild anlegen</button>
    </form>
    <br>
    
    <?
    // Liste anzeigen
    if (!count($data)) {
      echo 'Es konnten keine Bilder gefunden werden.';
    
    } else {
      ?>
      <table class="edittable">
      <tr>
      <td class="edittable">ID</td>
      <td class="edittable">width</td>
      <td class="edittable">height</td>
      <td class="edittable">ext</td>
      <td class="edittable">edit</td>
      <td class="edittable">delete</td>
      </tr>
      <?
      foreach ($data as $bild) {
        ?>
        <tr>
        <td class="edittable"><?= $bild['id'] ?></td>
        <td class="edittable"><?= $bild['width'] ?></td>
        <td class="edittable"><?= $bild['height'] ?></td>
        <td class="edittable"><?= $bild['ext'] ?></td>
        <td class="edittable" style="text-align:center;">
          <a href="javascript:launchEdit(<?= $bild['id'] ?>);"><img src="img/icon_edit.png" width="16" height="16"></a>
        </td>
        <td class="edittable" style="text-align:center;">
          <a href="javascript:launchDel(<?= $bild['id'] ?>,'Wollen Sie dieses Bild wirklich löschen?');"><img src="img/icon_delete.png" width="16" height="16"></a>
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

