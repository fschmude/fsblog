<?
session_start();
require_once '../lib/Page.php';
require_once '../lib/Parser.php';

$p = new Page();

// check login
if (!$_SESSION['ok']) $p->errmsg( 'Not logged in' );

$aid = isset($_GET['aid']) && $_GET['aid'] ? (int)$_GET['aid'] : 0;
if (!$aid) $p->errmsg( 'Keine sinnvolle aid: aid='.$aid ); 

// existierende Artikel holen
$dbh = $p->get_db();
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

$p->head( $titel, '', $datum );

$parser = new Parser( $p );
$text = $parser->parse( $text );
echo $text;

echo '<br><br>'."\n";
echo 'Kommentare...'."\n";

$p->foot();

