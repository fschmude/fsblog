<?
session_start();
require_once '../lib/Page.php';
$p = new Page();

$p->head('Edit');

if (!$_SESSION['ok']) $p->errmsg( 'Not logged in' );

$dbh = $p->get_db();
$aid = isset($_GET['aid']) ? (int) $_GET['aid'] : 0;
if (!$aid) {
  if (isset($_POST['datum']) && $_POST['datum']) {
    // insert
    $stmt = $dbh->prepare( "INSERT INTO artikel(titel,metadesc,text,datum,status) VALUES(:titel,:desc,:text,:datum,:status)" );
    $stmt->bindParam( ':titel', $_POST['titel'] );
    $stmt->bindParam( ':desc',  $_POST['desc'] );
    $stmt->bindParam( ':text',  $_POST['text'] );
    $stmt->bindParam( ':datum', $_POST['datum'] );
    $status = (int) $_POST['status'];
    $stmt->bindParam( ':status', $status );
    if (!$stmt->execute()) {
      $p->errmsg( 'Fehler beim Anlegen eines neuen Artikels' );
    }
    
    // get aid
    $stmt_li = $dbh->prepare( "SELECT SYSDATE(), LAST_INSERT_ID() aid FROM DUAL" );
    if (!$stmt_li->execute()) {
      $p->errmsg( 'Fehler beim Ermitteln der neuen aid' );
    }
    $row = $stmt_li->fetch();
    $aid = $row['aid'];
  }
  
} else {
  if (isset($_POST['datum']) && $_POST['datum']) {
    // edit
    $stmt = $dbh->prepare( "UPDATE artikel SET titel=:titel, metadesc=:desc, text=:text, datum=:datum, status=:status WHERE id=:aid" );
    $stmt->bindParam( ':aid', $aid );
    $stmt->bindParam( ':titel', $_POST['titel'] );
    $stmt->bindParam( ':desc', $_POST['desc'] );
    $stmt->bindParam( ':text', $_POST['text'] );
    $stmt->bindParam( ':datum', $_POST['datum'] );
    $stmt->bindParam( ':status', $_POST['status'] );
    if (!$stmt->execute()) {
      $p->errmsg( 'Fehler beim Editieren von Artikel aid='.$aid );
    }
  }
}

if ($aid) {
  // fetch
  $stmt = $dbh->prepare("SELECT * FROM artikel WHERE id=:aid");
  $stmt->bindParam( ':aid', $aid );
  if (!$stmt->execute()) {
    $p->errmsg( 'Fehler beim Holen des Artikels mit id='.$aid );
  }
  $row = $stmt->fetch();
  $titel = $row['titel'];
  $desc = $row['metadesc'];
  $text = $row['text'];
  $datum = $row['datum'];
  $status = $row['status'];
  echo 'Es wird editiert aid='.$aid;
  
} else {
  $datum = Date('Y-m-d H:i');
  $status = '0';
  echo 'Neuen Artikel publizieren:';
}


echo '<form method="post" action="a_edit.php?aid='.$aid.'">';
?>
  Datum (YYYY-MM-DD,hh:mm):
  <?
  echo '<input type="text" name="datum" value="'.$datum.'" style="width:150px">';
  ?>
  <br>
  Status:
  <?
  echo '<input type="text" name="status" value="'.$status.'" style="width:20px">';
  ?>
  (0=unsichtbar, 1=sichtbar in navi)
  <br>
  Titel:
  <br>
  <?
  echo '<input type="text" name="titel" value="'.$titel.'" style="width:600px">';
  ?>
  <br>
  Description (max 140):
  <br>
  <?
  echo '<input type="text" name="desc" value="'.$desc.'" maxlen="140" style="width:600px">';
  ?>
  <br>
  Text des Artikels:
  <br>
  <?
  echo '<textarea type="text" name="text" rows="15" style="width:600px">'.$text.'</textarea>';
  ?>
<br>
&lt;wiki href="wiki-Seitenname"&gt;verlinkter Text&lt;/wiki&gt;
<br>
&lt;imga id="bid"&gt; -&gt; <a href="b_list.php" target="_blank">Bilder verwalten</a>
<br>
&lt;h2&gt;Zwischenüberschrift&lt;/h2&gt; 
<br>
Als einziges sonst erlaubt: &lt;a...
<br>
<br>
ACHTUNG!
<br>
"Bloggen dient m.E. hauptsaechlich eher der Selbstbeweihraeucherung des Autors,
deswegen macht es ja Spass und jeder bloggt was das Zeug haelt.
<br>
An Fitel als Tip:
Wenn die Ueberhoehung der eigenen Position und die Darstellung der eigenen intellektuellen geradezu uebermenschlichen Faehigkeiten zu extrem wird, 
liest das Zeug keiner - 
man laesst sich als Leser nur ungern in eine Idiotenecke stellen.
<br>

<div align="center">
<input type="submit" value="Eintragen">
</div>
<?
echo '<a href="a_preview.php?aid='.$aid.'" target="_blank">Preview</a>';

?>
<br><br>
<a href="a_list.php">Zurück zur Artikelliste</a>

<?
$p->foot();

