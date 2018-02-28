<?
/**
 * View of all articles
 */
require_once PATH_PRIVATE.'V/VAdmin.php';

class VAdminBildList extends VAdmin {
  
  public function display($errmsg, $data) {
    $this->displayHead($errmsg, 'Artikelübersicht');

    $this->displayNaviLink('Bild_new', 'Neues Bild anlegen');
    echo ' &nbsp; | &nbsp; ';
    $this->displayNaviLink('Snippet_list', 'Schnippel-Übersicht');

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
        <?
        $this->displayEditIcon('Bild_up1', $bild['id']);
        ?>
        </td>
        <td class="tded" style="text-align:center;">
        <?
        $this->displayDelIcon('Bild_del', 'Bild', $bild['id']);
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

