<?
require_once '../lib/Page.php';
require_once 'MLeser.php';
class CLeser {
  
  private $errmsg = '';
  private $message = '';
  private $lines = null;
  
  public function __construct($get, $post) {
    try {
      if (!$_SESSION['ok']) {
        throw new Exception('Not logged in');
      }
      
      // delete?
      if (isset($get['del']) && $lid = (int) $get['del']) {
        MLeser::delete($lid);
        $this->message = 'Leseradresse Nr. '.$lid.' gelöscht.';
      }
      
      $this->lines = MLeser::get_all();
      
    } catch (Exception $e) {
      $this->errmsg = $e->getMessage();
    }
  }
  
  public function display() {  
    $p = new Page();
    
    $p->head('Abonnements');
    if ($this->errmsg) {
      $p->errmsg($this->errmsg);
      
    } else {
      if ($this->message) {
        echo $this->message;
        echo '<br><br>'."\n";
      }
      ?>
      <table style="width:80%;margin:0px;padding:0px;border-spacing:0px;border-collapse:collapse;border:0px;">
      <?
      $statusdesc = array(
        0 => 'unbestätigt',
        1 => 'bestätigt!'
      );
      if (is_array($this->lines)) {
        foreach ($this->lines as $line) {
          echo '<tr>';
          echo '<td class="tdc">'.$line['id'].'</td>'."\n";
          echo '<td class="tdc">'.$line['lmail'].'</td>'."\n";
          echo '<td class="tdc">'.$line['datum'].'</td>'."\n";
          echo '<td class="tdc">'.$line['code'].'</td>'."\n";
          echo '<td class="tdc">'.$line['status'].' - '.$statusdesc[$line['status']].'</td>'."\n";
          echo '<td><a href="leser.php?del='.$line['id'].'">löschen</a></td>';
          echo '</tr>';
        }
      }
      ?>
      </table>
      <br>
      <a href="index.php">Zurück zum Start</a>
      <?
    }
    $p->foot();
  }
}
