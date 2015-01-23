<?
/**
 * View of all articles
 */
require_once 'V/VAdmin.php';

class VAdminStart extends VAdmin {
  
  public function display($errmsg, $data) {
    
    $this->displayHead($errmsg, 'Backend Login');
    
    if (isset($data['msg']) && $msg = trim($data['msg'])) {
      echo $msg.'<br>';
    }
    ?>
    <form method="post" action="admin.php">
    <input type="password" name="pass" value="">
    <button type="submit">Login</button>
    </form>
    <br>
    
    <?
    $this->displayFoot();
  }

}
