<?
/**
 * Basisklasse für das ganze Backend
 */
require_once PATH_PRIVATE.'V/View.php';

abstract class VAdmin extends View {

  protected function displayHead($errmsg, $titel, $showStartLink = true) {
    header("Content-Type: text/html; charset=utf-8");
    ?><!DOCTYPE HTML>
    <html xmlns="http://www.w3.org/1999/xhtml" lang="de">
    <head>
    <meta charset="UTF-8">
    <title>FS: <?= $titel ?></title>
    <?
    echo '<link href="'.BASEURL.'static/styles.css" type="text/css" rel="stylesheet">';
    ?>
    </head>
    
    <body>
    <div style="margin:10px; padding:10px; width:900px;" class="tdc">
    <h1>Welcome to the backend</h1>
    
    <table width="100%">
    <tr>
    <td>
    <?
    if ($showStartLink) {
      ?>
      <a href="admin.php"><img src="<?= BASEURL ?>static/fslogo.png" style="border-width:0px; vertical-align:middle;">
      Backend Startseite </a>
      (Artikelübersicht)
      <?
    } else {
      echo '&nbsp;';
    }
    ?>
    </td>
    <td align="right">
    <a href="admin.php?action=logout" style="color:red; font-weight:bold;">Log out</a>
    </td>
    </tr>
    </table>
    
    <?
    if ($errmsg) {
      echo $errmsg;
      exit;
    }
  }
  
  protected function displayFoot() {
    ?>
    <a href="<?= BASEURL ?>">FS-Blog Startseite</a>
    
    <form name="frmAdminPage" method="post" action="admin.php">
    <input type="hidden" name="mode" id="mode">
    <input type="hidden" name="id" id="id">
    <input type="hidden" name="filter" id="filter">
    </form>
    
    <script src="static/admin.js" type="text/javascript"></script>
    
    </div>
    </body>
    </html>
    <?
  }


  /** 
   * Edit-Tipp anzeigen
   */
  protected function displayEditHint() {
    ?>
    Erlaubt:<br>
    &lt;h2&gt;Zwischenüberschrift&lt;/h2&gt; 
    <br>
    &lt;wiki href="wiki-Seitenname"&gt;verlinkter Text&lt;/wiki&gt;
    <br>
    &lt;imga id="bid"&gt;, &lt;video id="vid"&gt;
    <br>
    Als einziges sonst erlaubt: &lt;a...
    <br>
    <?
  }

  
  protected function displayNaviLink($mode, $label, $filter = 0) {
    echo '<a href="javascript:adminPage(\''.$mode.'\', 0, '.$filter.');">'.$label.'</a>';
  }
  
  protected function displayEditIcon($mode, $id) {
    echo '<a href="javascript:adminPage(\''.$mode.'\','.$id.', 0);"><img src="static/icon_edit.png" width="16" height="16"></a>';
  }

  protected function displayDelIcon($mode, $objektName, $id, $filter = 0) {
    echo '<a href="javascript:del(\''.$mode.'\',\''.$objektName.'\','.$id.','.$filter.');"><img src="static/icon_delete.png" width="16" height="16"></a>';
  }

  
  protected function displayInputHidden($name, $value) {
    echo '<input type="hidden" name="'.$name.'" value="'.$value.'">'."\n";
  }
  
}

