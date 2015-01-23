<?
require_once 'M/Model.php';
require_once 'V/View.php';
$v = new View();

// check login
//if (!$_SESSION['ok']) $v->errmsg( 'Not logged in' );

$aid = isset($_GET['aid']) && $_GET['aid'] ? (int)$_GET['aid'] : 0;
if (!$aid) $v->errmsg( 'Keine sinnvolle aid: aid='.$aid ); 

// existierende Artikel holen
$m = new Model();
$dbh = $m->get_pdo();
$stmt = $dbh->prepare( "SELECT * FROM artikel WHERE id=:aid" );
$stmt->bindParam( ':aid', $aid );
if (!$stmt->execute()) {
  $p->errmsg( 'Fehler beim Holen des Artikels mit id='.$aid );
}
$row = $stmt->fetch();
if (!$row) {
  $p->errmsg( 'Es konnte zu aid='.$aid.' kein Artikel gefunden werden.' );
}
$titel = $row['titel'];
$text = $row['text'];
$datum = Date( 'Y-m-d H:i', strtotime($row['datum']) );

$v->head( $titel, '', $datum );

$text = $v->parse_artikel($text, array());
echo $text;

echo '<br><br>'."\n";
echo 'Kommentare...'."\n";

$v->foot();

