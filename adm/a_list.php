<?
session_start();
require_once '../lib/Page.php';
$p = new Page();

$p->head('Artikelliste');

// check login, only here password entry is possible
if (!isset($_SESSION['ok']) || !$_SESSION['ok']) {
  $pass = isset($_POST['pass']) ? $_POST['pass'] : '';
  if (!$pass) {
    $p->errmsg( 'Not logged in' );
  }
  if ($pass != 'Fritze69') {
    $p->errmsg( 'Falsches Passwort' );
  }
  $_SESSION['ok'] = true;
}
?>

<a href="index.php">Zurück zum Start</a>
<br><br>

<a href="b_list.php">Bilder verwalten</a>
<br><br>

<?
// existierende Artikel holen
$dbh = $p->get_db();
$stmt = $dbh->prepare(
  "SELECT a.id aid,a.titel,a.status,p.anz,p1.anz1"
  ." FROM artikel a"
  ." LEFT JOIN (SELECT aid, count(*) anz1"
  ."  FROM posts"
  ."  WHERE status=1"
  ."  GROUP BY aid"
  ." ) p1 ON a.id=p1.aid"
  ." LEFT JOIN (SELECT aid, count(*) anz"
  ."  FROM posts"
  ."  GROUP BY aid"
  ." ) p ON a.id=p.aid"
  ." ORDER BY aid DESC"
);
if (!$stmt->execute()) {
  $p->errmsg( 'Fehler beim Holen der Artikelüberschriften' );
}
?>
<table style="width:80%;margin:0px;padding:0px;border-spacing:0px;border-collapse:collapse;border:0px;">
<tr>
<td class="tdc">+</td>
<td class="tdc"><a href="a_edit.php">Neuer Artikel</a></td>
</tr>
<?
$status_desc = array(
  0 => 'unsichtbar...',
  1 => 'sichtbar'
);
while ($row = $stmt->fetch()) {
  echo '<tr>';
  echo '<td class="tdc">'.$row['aid'].'</td>';
  echo '<td class="tdc"><a href="a_edit.php?aid='.$row['aid'].'">'.$row['titel'].'</a></td>';
  echo '<td class="tdc">'.$status_desc[$row['status']].'</td>';
  echo '<td class="tdc">';
  $anz = (int)$row['anz'];
  if ($anz) {
    echo '<a href="p_list.php?aid='.$row['aid'].'"';
    $b_hint = ($row['anz1'] > 0);
    if ($b_hint) {
      echo ' style="font-weight:bold;"';
    }
    echo '>';
  }
  echo $anz.' Postings';
  if ($anz) {
    echo '</a>';
  }
  if ($b_hint) {
    echo ' &lt;- '.$row['anz1'].' Posts sind freizuschalten!';
  }
  echo '</td>';
  echo '<td class="tdc"><a href="../artikel.php?aid='.$row['aid'].'" target="_blank">Testlink</a></td>';
  echo '</tr>'."\n";
}
?>
</table>                        
<br><br>

<a href="index.php">Zurück zum Start</a>
<br><br>
<?
$p->foot();

