<?
session_start();
require_once '../lib/Page.php';
$p = new Page();

$p->head('Liste der Postings');

if (!$_SESSION['ok']) $p->errmsg( 'Not logged in' );

// input
if (!isset($_GET['aid']) || !$_GET['aid']) {
  $p->errmsg( 'No aid' );
}
$aid = $_GET['aid'];

// freischalten
$dbh = $p->get_db();
if (isset($_GET['rel']) && $_GET['rel']) {
  $pid = $_GET['rel'];
  $stmt = $dbh->prepare( "UPDATE posts SET status=2 WHERE id=:pid" );
  $stmt->bindParam( ':pid', $pid );
  if (!$stmt->execute()) {
    $p->errmsg( 'Fehler beim Freischalten von pid='.$pid );
  }
}

// löschen
if (isset($_GET['del']) && $_GET['del']) {
  $pid = $_GET['del'];
  $stmt = $dbh->prepare( "DELETE FROM posts WHERE id=:pid" );
  $stmt->bindParam( ':pid', $pid );
  if (!$stmt->execute()) {
    $p->errmsg( 'Fehler beim Löschen von pid='.$pid );
  }
}

// existierende Postings holen
$stmt = $dbh->prepare(
  "SELECT * FROM posts WHERE aid=:aid"
  ." ORDER BY id ASC"
);
$stmt->bindParam( ':aid', $aid );
if (!$stmt->execute()) {
  $p->errmsg( 'Fehler beim Holen der Postings' );
}

$statusdesc = array(
  0 => 'unbestätigt',
  1 => 'bestätigt',
  2 => 'frei',
  3 => 'gelöscht'
);

$i = 0;
while ($row = $stmt->fetch()) {
  $i++;
  if ($i == 1) {
    echo 'Postings zu Artikel aid='.$aid.'<br>'."\n";
    ?>
    <table style="width:100%;align:center;margin:0px;padding:0px;border-spacing:0px;border-collapse:collapse;">
    <tr>
    <td class="tdc">lfnr</td>
    <td class="tdc">code</td>
    <td class="tdc">username + usermail</td>
    <td class="tdc">datum</td>
    <td class="tdc">text</td>
    <td class="tdc">status</td>
    </tr>
    <?
  }
  echo '<tr>';
  echo '<td class="tdc">'.$row['lfnr'].'</td>';
  echo '<td class="tdc">'.$row['code'].'</td>';
  echo '<td class="tdc"><a href="mailto:'.$row['usermail'].'">'.$row['username'].'</a></td>';        
  echo '<td class="tdc">'.$row['datum'].'</td>';
  echo '<td class="tdc">'.$row['text'].'</td>';
  $b_hint = ($row['status'] == 1);
  echo '<td class="tdc"';
  if ($b_hint) {
    echo ' style="font-weight:bold;"';
  }
  echo '>'.$row['status'].' ('.$statusdesc[$row['status']].')</td>';
  echo '<td>';
  if ($b_hint) {
    echo '<a href="p_list.php?aid='.$row['aid'].'&rel='.$row['id'].'">freischalten</a>';
    echo ' ';
  } 
  echo '<a href="p_list.php?aid='.$row['aid'].'&del='.$row['id'].'">löschen</a>';
  echo '</td>';
  echo '</tr>'."\n";
}

if ($i==0) {
  echo 'Es gibt keine Postings zu Artikel aid='.$aid;
} else {
  echo '</table>';
}
?>
<br><br>
<a href="a_list.php">Zurück zur Artikelliste</a>

<?
$p->foot();

