<?
require_once '../lib/Page.php';
class CStart {
  
  private $errmsg = '';
  private $message = '';
  private $display = '';
  
  public function __construct($get, $post) {
    try {
      //throw new Exception(__FILE__.':'.__LINE__.': Na sowas');
      if (isset($get['action']) && $get['action'] == 'logout') {
        $_SESSION['ok'] = false;
        $this->display = 'login';
        return;
      }
      
      if (!isset($_SESSION['ok']) || !$_SESSION['ok']) {
        $pass = isset($post['pass']) ? $post['pass'] : '';
        if (!$pass) {
          $this->message = 'Not logged in';
          $this->display = 'login';
          return;
        }
        if ($pass != 'Fritze69') {
          $this->message = 'Falsches Passwort';
          $this->display = 'login';
          return;
        }
        $_SESSION['ok'] = true;
        $this->display = 'menu';
        return;
        
      } else {
        // session ok
        $this->display = 'menu';
      }
      
    } catch (Exception $e) {
      $this->errmsg = $e->getMessage();
    }
  }
  
  public function display() {  
    $p = new Page();
    
    $p->head('Admin-Zugang');
    if ($this->errmsg) {
      $p->errmsg($this->errmsg);
      
    } else {
      if ($this->message) {
        echo $this->message;
        echo '<br><br>'."\n";
      }
      switch ($this->display) {
      case 'menu':
        ?>
        -&gt; <a href="a_list.php">Artikel</a>
        <br>
        -&gt; <a href="b_list.php">Bilder (und andere Downloads)</a>
        <br>
        -&gt; <a href="leser.php">Leseradressen</a>
        <br>
        <br>
        -&gt; <a href="index.php?action=logout">Log out</a>
        <br>
        <?
        break;
        
      case 'login':
      default:
        ?>
        <form method="post" action="index.php">
        <input type="password" name="pass" value="">
        <input type="submit" value="Eintreten">
        </form>
        <?
      }
    }
    $p->foot();
  }
}
