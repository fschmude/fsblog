<?
/**
 * View of all articles
 */
require_once 'V/VAdmin.php';

class VAdminArtikelList extends VAdmin {
  
  public function display($errmsg, $data) {
    $this->displayHead($errmsg, 'Artikelübersicht');

    ?>
    <form method="post" action="admin.php">
    <input type="hidden" name="mode" value="Artikel_new">
    <button type="submit">Neuen Artikel anlegen</button>
    </form>
    <br>
    
    <form action="admin.php" method="post">
    <input type="hidden" value="Bild_list" name="mode"></input>
    <input type="submit" value="Bilder verwalten">
    </form>
    
    <?
    // Liste anzeigen
    if (!count($data)) {
      echo 'Es konnten keine Artikel gefunden werden.';
    
    } else {
      ?>
      <table class="edittable">
      <tr>
      <td class="edittable">ID</td>
      <td class="edittable">Erscheinungsdatum</td>
      <td class="edittable">URL (click to view)</td>
      <td class="edittable">Status</td>
      <td class="edittable">edit</td>
      <td class="edittable">delete</td>
      <td class="edittable">Mailtext</td>
      </tr>
      <?
      foreach ($data as $artikel) {
        ?>
        <tr>
        <td class="edittable"><?= $artikel['id'] ?></td>
        <td class="edittable"><?= $artikel['datum'] ?></td>
        <td class="edittable"><a href="artikel/<?= $artikel['url'] ?>.htm" target="_blank"><?= $artikel['url'] ?></a></td>
        <td class="edittable"><?= $artikel['status'] ?></td>
        <td class="edittable" style="text-align:center;">
          <a href="javascript:launchEdit(<?= $artikel['id'] ?>);"><img src="img/icon_edit.png" width="16" height="16"></a>
        </td>
        <td class="edittable" style="text-align:center;">
          <a href="javascript:launchDel(<?= $artikel['id'] ?>,'Wollen Sie den Artikel mit ID=<?= $artikel['id'] ?> wirklich löschen?');"><img src="img/icon_delete.png" width="16" height="16"></a>
        </td>
        <td class="edittable">
          <a href="mailtext.php?aid=<?= $artikel['id'] ?>" target="_blank">Mailtext</a>
        </td>
        </tr>
        <?
      }
      ?>
      </table>
      
      <?
      $this->displayLinkForm('Edit', 'Artikel_up1', true);
      $this->displayLinkForm('Del', 'Artikel_del', true);
    }
    
    $this->displayFoot();
  }

}

