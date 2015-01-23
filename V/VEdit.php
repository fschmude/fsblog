<?
/**
 * Abstract View class for all edit views
 */
require_once 'V/View.php';

abstract class VEdit extends View {

  // lookup for article statuses
  private $artStat;
  
  
  /**
   * Constructor
   */
  public function __construct() {
    // init lookups
    $this->artStats = array(
      0 => 'Nicht veröffentlicht',
      1 => 'Veröffentlicht, aber nicht in Magazin',
      2 => 'Veröffentlicht'
    );
  }
  
  
  /**
   * lookup values, formatted for a menulist
   * @return array (
   *    array('id' => 0, 'label' => ...),
   *    array('id' => 1, ...),
   *    ...
   * );
   */
  protected function getArtStats() {
    $result = array();
    foreach ($this->artStats as $id => $status) {
      $result[] = array('id' => $id, 'label' => $status);
    }
    return $result;
  }
  
  
  /**
   * Lookup function
   */
  protected function getArtStat($key) {
    return $this->artStats[$key];
  }

  
  /**
   * The edit mode head is different from the view mode head
   * So we must extend it here.
   * @param $errmsg
   * @param $title H1
   * @param $bArtikelListLink if the link to the article list should be displayed
   * @param $sCenter optional HTML text in the middle
   */
  protected function displayHead($errmsg, $title, $bArtikelListLink = true, $sCenter = '&nbsp;') {
    parent::displayHead($errmsg, $title);

    // Subtitel-Zeile für alle Edit-Formulare
    ?>
    <table width="100%">
    <tr>
    <td width="30%">
      <?
      if ($bArtikelListLink) {
        ?>
        <a href="javascript:launchArtikelList();">Zurück zur Artikelliste</a>
        <?
        $this->displayLinkForm('ArtikelList', 'Artikel_list', false);
      } else {
        echo '&nbsp;';
      }
      ?>
    </td>
    <td width="40%" style="text-align:center;">
      <?= $sCenter ?>
    </td>
    <td width="30%" style="text-align:right;">
      <form method="post" id="frmLogout" action="edit">
      <input type="hidden" name="mode" value="logout">
      <button type="submit">Log out</button>
      </form>
    </td>
    </tr>
    </table>
    <br>
    
    <?
  }
  
  
  /**
   * Display a textbox with a label
   */
  protected function displayLine($label, $name, $value, $isMandatory = false) {
    ?>
    <table width="100%">
    <tr>
    <td style="width:40%; text-align:right;">
      <?
      echo $label;
      if ($isMandatory) {
        echo '<sup style="color:#ff0000;">*</sup>';
      }
      ?>
    </td>
    <td style="width:60%;">
      <input type="text" id="<?= $name ?>" name="<?= $name ?>" value="<?= $value ?>" style="width:80%" onBlur="javascript:blurhint(this.id);">
      <input type="hidden" id="<?= $name ?>_old" value="<?= $value ?>">
    </td>
    </tr>
    </table>
    <?
  }

  
  /**
   * Display a menulist with a label
   */
  protected function displayMenulist($caption, $name, $options, $value, $add = '') {
    ?>
    <table width="100%">
    <tr>
    <td style="width:40%; text-align:right;">
      <?= $caption ?>
    </td>
    <td style="width:60%;">
      <select name="<?= $name ?>" id="<?= $name ?>" onBlur="javascript:blurhint(this.id);">
      <?
      foreach ($options as $option) {
        echo '<option value="'.$option['id'].'"';
        if ($option['id'] == $value) {
          echo ' selected="true"';
        }
        echo '>'.$option['label'].'</option>'."\n";
      }
      ?>
      </select>
      <input type="hidden" id="<?= $name ?>_old" value="<?= $value ?>">
      
      <?
      if ($add) {
        echo ' &nbsp; ';
        echo $add;
      }
      ?>
    </td>
    </tr>
    </table>
    <?
  }
  
  
  protected function displayInputHidden($name, $value) {
    echo '<input type="hidden" name="'.$name.'" value="'.$value.'">'."\n";
  }
  
  
  /**
   * display a hidden form, which serves to launch http posts
   * Usage: display this form, and offer links with href="javascript:launchName(id);"
   * or href="javascript:launchName();"
   * Caution: Dont display a form inside a form!
   * Rather place it at the bottom of your page.
   */
  protected function displayLinkForm($name, $mode) {
    // display form
    echo '<form id="frm'.$name.'" method="post" action="'.BASEURL.'edit">';
    echo '<input type="hidden" name="mode" value="'.$mode.'">';
    echo '<input type="hidden" name="id" value="">';
    echo '</form>';
    
    // display launch script
    ?>
    <script type="text/javascript">
    function launch<?= $name ?>(id, question) {
      if (question && !confirm(question)) {
        return;
      }
      
      var frm = document.getElementById('frm<?= $name ?>');
      if (id > 0) {
        frm.id.value = id;
      }
      frm.submit();
    }
    </script>
    <?
  }

  
  /**
   * Footer for edit forms: They may contain a message for the editor
   */
  protected function displayFoot($data = null) {
    // is there a message for the editor?
    if (isset($data['message']) && $msg = trim($data['message'])) {
      ?>
      <script type="text/javascript">
      alert('<?= $msg ?>');
      </script>
      <?
    }
    
    // all edit forms need the blurhint function
    ?>
    <script type="text/javascript" src="js/blurhint.js"></script>
    <?
    
    parent::displayFoot();
  }
  
}

