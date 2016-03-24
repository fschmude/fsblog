<?
/**
 * View of all articles
 */
require_once 'V/VAdmin.php';

class VAdminStart extends VAdmin {
  
  public function display($errmsg, $data) {
    
    $this->displayHead($errmsg, 'Backend Login', false);
    
    if (isset($data['msg']) && $msg = trim($data['msg'])) {
      echo $msg.'<br>';
    }
    ?>
    <form method="post" action="admin.php">
    <input type="password" name="pass" id="pass" value="">
    <button type="submit">Login</button>
    </form>
    <br>
    
    <script type="text/javascript">
    var pw = document.getElementById('pass');
    pw.focus();
    </script>
    <?
    $this->displayFoot();
  }

}
