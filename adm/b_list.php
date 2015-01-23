<?
session_start();
require_once '../lib/Page.php';
$p = new Page();

$p->head('Bilderliste');

if (!$_SESSION['ok']) $p->errmsg( 'Not logged in' );
?>
<a href="index.php">Zurück zum Start</a>
<br><br>

<a href="a_list.php">Artikel verwalten</a>
<br><br>

<?
// Wurde etwas upgeloaded?
$dbh = $p->get_db();
if (isset($_FILES['imagefile']['name']) && strlen($_FILES['imagefile']['name'])) {
  $aInfos = getimagesize( $_FILES['imagefile']['tmp_name'] );
  $w = $aInfos[0];
  $h = $aInfos[1];
  
  // neuer Eintrag, neue id
  $stmt = $dbh->prepare( "INSERT INTO bilder(width,height,ext) VALUES(:w,:h,:ext)" );
  $aName = explode( '.', $_FILES['imagefile']['name'] );
  $ext = $aName[count($aName) - 1];
  $stmt->bindParam( ':w', $w );
  $stmt->bindParam( ':h', $h );
  $stmt->bindParam( ':ext', $ext );
  if (!$stmt->execute()) {
    $p->errmsg( 'Fehler beim Eintragen des neuen Bildes' );
  }
  $stmt_li = $dbh->prepare( "SELECT LAST_INSERT_ID() bid FROM DUAL" );
  if (!$stmt_li->execute()) {
    $p->errmsg( 'Fehler beim Ermitteln der neuen bid' );
  }
  $row = $stmt_li->fetch();
  $bid = $row['bid'];
  
  // Bilddatei speichern
  $target_file = $bid.'.'.$ext;
  $target_path = '../imga/'.$target_file;
  if (!move_uploaded_file($_FILES['imagefile']['tmp_name'], $target_path)) {
    $p->errmsg( 'Fehler in move_uploaded_files' );
  }
  echo 'Bild hochgeladen: '.$target_file.', width='.$w.', height='.$h.'<br>'."\n";
}

// löschen?
if (isset($_GET['del']) && strlen($_GET['del'])) {
  $bid = (int) $_GET['del'];
  $stmt = $dbh->prepare( "DELETE FROM bilder WHERE id=:bid" );
  $stmt->bindParam( ':bid', $bid );
  if (!$stmt->execute()) {
    $p->errmsg( 'Fehler beim Löschen in Tabelle bilder' );
  }
  $path_del = '../imga/'.$bid.'.*';
  unlink( $path_del ); 
  echo 'gelöscht: '.$path_del;
}

// Bilder holen
$stmt = $dbh->prepare( "SELECT * FROM bilder" );
if (!$stmt->execute()) {
  $p->errmsg( 'Fehler beim Holen der Bilder' );
}
?>
<table style="width:80%;margin:0px;padding:0px;border-spacing:0px;border-collapse:collapse;border:0px;">
<tr>
<td class="tdc" style="text-align:right;">Neues Bild:</td>
<td class="tdc">
  <form method="post" action="b_list.php" enctype="multipart/form-data">
  <input name="imagefile" type="file"> (png/jpg/gif?) &nbsp;
  <input type="submit" value="Upload">
  </form>
</td>
<td class="tdc">Breite</td>
<td class="tdc">Höhe</td>
</tr>
<?
while ($row = $stmt->fetch()) {
  $height = $row['height'];
  $width = $row['width'];
  if ($row['height'] > 30) {
    $height = 30;
    $width = (int) ($row['width'] / $row['height'] * 30);
  }                                                     
  echo '<tr>';
  echo '<td class="tdc">'.$row['id'].'.'.$row['ext'].'</td>';
  echo '<td class="tdc" style="text-align:center"><img src="'.$p->get_baseurl().'imga/'.$row['id'].'.'.$row['ext'].'"'
    .' width="'.$width.'" height="'.$height.'">'
    .'</td>'
  ;
  echo '<td class="tdc">'.$row['width'].'</td>';
  echo '<td class="tdc">'.$row['height'].'</td>';
  echo '<td class="tdc"><a href="b_list.php?del='.$row['id'].'">löschen</a></td>';
  echo '</tr>'."\n";
}
?>
</table>                        
<br><br>

<?
$p->foot();

