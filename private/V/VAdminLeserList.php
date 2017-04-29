<?
/**
 * View of all readers
 */
require_once PATH_PRIVATE.'V/VAdmin.php';

class VAdminLeserList extends VAdmin {
  
  /**
   * display list of readers
   * @param string $errmsg
   * @param array $data = array(...)
   */
  public function display($errmsg, $data) {
    $titel = 'Abonnenten';
    $this->displayHead($errmsg, $titel);
    
    echo $titel;
    echo '<br>'."\n";
    
    $this->displayNaviLink('Leser_new', 'Neuen Abonnenten anlegen');
    echo '<br>'."\n";

    // Liste anzeigen
    if (!count($data['rows'])) {
      echo 'Es konnten keine Leser gefunden werden.<br>';
    
    } else {
      ?>
      <table class="tded">
      <tr>
      <td class="tded">ID</td>
      <td class="tded">Mail</td>
      <td class="tded">Datum</td>
      <td class="tded">Code</td>
      <td class="tded">Status</td>
      <td class="tded">edit</td>
      <td class="tded">delete</td>
      </tr>
      <?
      foreach ($data['rows'] as $leser) {
        ?>
        <tr>
        <td class="tded"><?= $leser['id'] ?></td>
        <td class="tded"><?= $leser['lmail'] ?></td>
        <td class="tded"><?= $leser['datum'] ?></td>
        <td class="tded"><?= $leser['code'] ?></td>
        <td class="tded"><?= $leser['status'] ?></td>
        <td class="tded" style="text-align:center;">
        <?
        $this->displayEditIcon('Leser_up1', $leser['id']);
        ?>
        </td>
        <td class="tded" style="text-align:center;">
        <?
        $this->displayDelIcon('Leser_del', 'Abonnent', $leser['id']);
        ?>
        </td>
        </tr>
        <?
      }
      ?>
      </table>
      <br>
      
      <?
    }
    
    $this->displayFoot();
  }

}
