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
      <table class="tded">
      <tr>
      <td class="tded">ID</td>
      <td class="tded">Erscheinungsdatum</td>
      <td class="tded">URL (click to view)</td>
      <td class="tded">Status</td>
      <td class="tded">edit</td>
      <td class="tded">delete</td>
      <td class="tded">Mailtext</td>
      </tr>
      <?
      foreach ($data as $artikel) {
        ?>
        <tr>
        <td class="tded"><?= $artikel['id'] ?></td>
        <td class="tded"><?= $artikel['datum'] ?></td>
        <td class="tded"><a href="<?= $this->completeUrl($artikel['url']) ?>" target="_blank"><?= $artikel['url'] ?></a></td>
        <td class="tded"><?= $artikel['status'] ?></td>
        <td class="tded" style="text-align:center;">
          <a href="javascript:launchEdit(<?= $artikel['id'] ?>);"><img src="img/icon_edit.png" width="16" height="16"></a>
        </td>
        <td class="tded" style="text-align:center;">
          <a href="javascript:launchDel(<?= $artikel['id'] ?>,'Wollen Sie den Artikel mit ID=<?= $artikel['id'] ?> wirklich löschen?');"><img src="img/icon_delete.png" width="16" height="16"></a>
        </td>
        <td class="tded">
          <?
          if ($artikel['status'] == 1) {
            echo '<a href="mailtext.php?aid='.$artikel['id'].'" target="_blank">Mailtext</a>';
          } else {
            echo 'Status != 1';
          }
          ?>
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

