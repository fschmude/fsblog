<?
/**
 * edit form 1 reader
 */
require_once 'V/VAdmin.php';

class VAdminLeser extends VAdmin {
  
  /**
   * display the edit form for a single reader
   * @param string $errmsg
   * @param array $data = array(...)
   * );
   */
  public function display($errmsg, $leser) {
    $this->displayHead($errmsg, 'Leser editieren');
    echo '<br>'."\n";

    $this->displayNaviLink('Leser_list', 'Zurück zur Leser-Übersicht');
    echo '<br><br>'."\n";
    
    echo 'Leser Nr. '.$leser['id'].'<br>';
    ?>
    
    <form method="post" action="admin.php">
    <input type="hidden" name="mode" value="Leser_up">
    <input type="hidden" name="id" value="<?= $leser['id'] ?>">
    E-Mail:
    <br>
    <input type="text" name="lmail" value="<?= $leser['lmail'] ?>" style="width:200px">
    <br>
    Datum:
    <br>
    <input type="text" name="datum" value="<?= $leser['datum'] ?>" style="width:200px">
    <br>
    Code:
    <br>
    <input type="text" name="code" value="<?= $leser['code'] ?>" style="width:200px">
    <br>
    Status (0=unbestätigt, 1=bestätigt):
    <br>
    <input type="text" name="status" value="<?= $leser['status'] ?>" style="width:100px">
    <br>
    
    <input type="submit" value="abschicken">
    
    </form>

    <?
    $this->displayNaviLink('Leser_list', 'Zurück zur Leser-Übersicht');
    echo '<br><br>'."\n";

    $this->displayFoot();
  }

}
