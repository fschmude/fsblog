<?
/**
 * View of all vids
 */
require_once 'V/VAdmin.php';

class VAdminVideoList extends VAdmin {
  
  public function display($errmsg, $data) {
    $this->displayHead($errmsg, 'Artikelübersicht');
    
    $this->displayNaviLink('Video_new', 'Neues Video anlegen');
    echo '<br>'."\n";

    // Liste anzeigen
    if (!count($data['rows'])) {
      echo 'Es konnten keine Videos gefunden werden.';
    
    } else {
      ?>
      <table class="tded">
      <tr>
      <td class="tded">ID</td>
      <td class="tded">width</td>
      <td class="tded">height</td>
      <td class="tded">vname</td>
      <td class="tded">edit</td>
      <td class="tded">delete</td>
      </tr>
      <?
      foreach ($data['rows'] as $vid) {
        ?>
        <tr>
        <td class="tded"><?= $vid['id'] ?></td>
        <td class="tded"><?= $vid['width'] ?></td>
        <td class="tded"><?= $vid['height'] ?></td>
        <td class="tded"><?= $vid['vname'] ?></td>
        <td class="tded" style="text-align:center;">
          <a href="javascript:launchEdit(<?= $vid['id'] ?>);"><img src="img/icon_edit.png" width="16" height="16"></a>
        </td>
        <td class="tded" style="text-align:center;">
          <a href="javascript:launchDel(<?= $vid['id'] ?>,'Wollen Sie das Video mit ID=<?= $vid['id'] ?> wirklich löschen?');"><img src="img/icon_delete.png" width="16" height="16"></a>
        </td>
        </tr>
        <?
      }
      ?>
      </table>
      
      <?
      $this->displayLinkForm('Edit', 'Video_up1', true);
      $this->displayLinkForm('Del', 'Video_del', true);
    }
    
    $this->displayFoot();
  }

}

