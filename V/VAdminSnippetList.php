<?
/**
 * View of all (or the latest) snips
 */
require_once 'V/VAdmin.php';

class VAdminSnippetList extends VAdmin {
  
  /**
   * display list of snippets for a whole month
   * @param string $errmsg
   * @param array $data = array(
   *    'month' => '201604',
   *    'months' => array('201601, '201602', ...),
   *    'rows' => array(...),
   
   * );
   */
  public function display($errmsg, $data) {
    if (!$month = $data['month']) {
      echo 'Kein Monat angegeben!';
    }
    $titel = 'Schnippel zu Monat '.$data['month'];
    $this->displayHead($errmsg, $titel);
    
    echo $titel;
    echo '<br>'."\n";
    
    echo 'Alle Schnippel von Monat ';
    echo '<select id="selMonatsFilter">';
    foreach ($data['allMonths'] as $month) {
      echo '<option value="'.$month.'">'.$month.'</option>'."\n";
    }
    echo '</select>'."\n";
    ?>
    <a href="javascript:monatFiltern();">anzeigen</a>
    <script type="text/javascript">
    function monatFiltern() {
      var sel = document.getElementById('selMonatsFilter');
      var month = sel.options[sel.selectedIndex].value;
      adminPage('Snippet_list', 0, month);
    }
    </script>
    <br>
    
    <?
    $this->displayNaviLink('Snippet_new', 'Neuen Schnippel anlegen', $month);
    echo '<br>'."\n";

    // Liste anzeigen
    if (!count($data['rows'])) {
      echo 'Es konnten keine Schnippel gefunden werden.<br>';
    
    } else {
      ?>
      <table class="tded">
      <tr>
      <td class="tded">ID</td>
      <td class="tded">Datum</td>
      <td class="tded">Text (Beginn)</td>
      <td class="tded">edit</td>
      <td class="tded">delete</td>
      </tr>
      <?
      foreach ($data['rows'] as $snip) {
        ?>
        <tr>
        <td class="tded"><?= $snip['id'] ?></td>
        <td class="tded"><?= $snip['datum'] ?></td>
        <td class="tded"><a href="<?= $snip['url'] ?>" target="_blank"><?= $snip['text'] ?></a></td>
        <td class="tded" style="text-align:center;">
        <?
        $this->displayEditIcon('Snippet_up1', $snip['id']);
        ?>
        </td>
        <td class="tded" style="text-align:center;">
        <?
        $this->displayDelIcon('Snippet_del', 'Schnippel', $snip['id'], $data['month']);
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

