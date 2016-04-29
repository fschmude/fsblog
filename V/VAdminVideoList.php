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
        <?
        $this->displayEditIcon('Video_up1', $vid['id']);
        ?>
        </td>
        <td class="tded" style="text-align:center;">
        <?
        $this->displayDelIcon('Video_del', 'Video', $vid['id']);
        ?>
        </td>
        </tr>
        <?
      }
      ?>
      </table>
      <?
    }
    
    $this->displayFoot();
  }

}

