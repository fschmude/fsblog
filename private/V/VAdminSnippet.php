<?
/**
 * edit form 1 snip
 */
require_once PATH_PRIVATE.'V/VAdmin.php';

class VAdminSnippet extends VAdmin {
  
  /**
   * display the edit form for a single snip
   * @param string $errmsg
   * @param array $data = array(
   *    'month' => '201604',
   *    'rows' => array(...)
   * );
   */
  public function display($errmsg, $snip) {
    $this->displayHead($errmsg, 'Schnippel editieren');
    echo '<br>'."\n";

    $this->displayNaviLink('Snippet_list', 'Schnippel-Übersicht', $snip['month']);
    echo '<br><br>'."\n";
    
    echo 'Schnippel Nr. '.$snip['id'].'<br>';
    ?>
    
    <form method="post" action="admin.php">
    <input type="hidden" name="mode" value="Snippet_up">
    <input type="hidden" name="id" value="<?= $snip['id'] ?>">
    <input type="hidden" name="filter" value="<?= $snip['month'] ?>">
    <br>
    Datum:
    <input type="text" name="datum" value="<?= $snip['datum'] ?>" style="width:200px">
    <br>
    Text:
    <table width="100%">
    <tr>
    <td rowspan="2">
      <textarea type="text" name="text" id="text" rows="15" style="width:100%"><?= $snip['text'] ?></textarea>
    </td>
    <td>
      <?
      foreach ($snip['lastImgas'] as $imga) {
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
    Facebook-Post-ID:
    <input type="text" name="fbid" value="<?= $snip['fbid'] ?>" style="width:200px">
    <br>
    <input type="submit" value="Schnippel ändern">
    </form>
    <a href="<?= $snip['url'] ?>" target="_blank">Preview</a>
    <br>

    <?
    $this->displayFoot();
  }

}
