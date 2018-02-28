<?
require_once PATH_PRIVATE.'V/IView.php';

abstract class View implements IView {
  
  protected function head($titel, $canonical = '', $datum = '', $desc = '', $robots = 'index, follow') {
    header("Content-Type: text/html; charset=utf-8");
    ?><!DOCTYPE HTML>
    <html xmlns="http://www.w3.org/1999/xhtml" lang="de">
    <head>
    <?
    echo '<meta name="robots" content="'.$robots.'">'."\n";
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?
    if ($desc) {
      echo '<meta name="description" content="'.$desc.'">'."\n";
    }
    
    echo '<title>FS: '.$titel.'</title>'."\n";
    
    echo '<link href="'.BASEURL.'static/styles.css" type="text/css" rel="stylesheet">'."\n";
    echo '<link rel="SHORTCUT ICON" href="'.BASEURL.'static/favicon.ico">'."\n";
    if ($canonical) {
      echo '<link rel="canonical" href="'.$canonical.'" />'."\n";
    }
    echo '<link rel="alternate" type="application/rss+xml" title="RSS" href="'.BASEURL.'rss.php" />'."\n";
    ?>
    </head>
    <body>
    <div class="lt1">
    <div class="icon">
    <br>
    <?
    echo '<a href="'.BASEURL.'"><img src="'.BASEURL.'static/fslogo.png" style="border-width:0px;" alt="Logo"></a>'."\n";
    ?>
    </div>
    
    <div class="header">
    <h1>FS-Blog</h1>
    Das freisinnige Blog von Fritz Schmude.
    </div>
    </div><!-- lt1 -->
    
    <div class="lt2">
    <div class="content">
    <div class="inside">
      <table style="width:100%;margin:0px;padding:0px;border-spacing:0px;border-collapse:collapse; background-color: #ffffff;">
      <tr>
      <td width="50%">
        <h2><?= $titel ?></h2>
      </td>
      <td style="text-align:right">
        <?
        if ($datum) {
          echo date('Y-m-d H:i', strtotime($datum));
        }
        ?>
      </td>
      </tr>
      </table>
      <div>
    <?
  }

  protected function foot($navi_arts = array()){
    ?>
    </div>
    </div><!-- inside -->
    </div><!-- content -->
    
    <div class="navi">
    <div class="inside">
      <?
      // Link zu START
      echo '<a href="'.BASEURL.'">Start</a>'."\n";
      echo '<br><br>'."\n";
      
      // Artikelliste
      echo 'Neuere Artikel:<br>'."\n";
      foreach ($navi_arts as $art) {
        echo '<a href="'.$art['url'].'">'.Date('Y-m-d', strtotime($art['datum'])).':<br>'.$art['titel'].'</a>';
        echo '<br><br>'."\n";
      }
      
      echo '<a href="'.BASEURL.'alle.php">Alle Artikel</a>';
      echo '<br><br>'."\n";
      
      // Mail-Feature
      ?>
      <form method="post" action="<?= BASEURL ?>confirm.php">
      Ich will bei neuen Artikeln informiert werden
      <br>
      (E-Mail-Adresse eingeben):
      <br>
      <div style="text-align:center;">
      <input type="email" id="lmail" name="lmail" class="abobox">
      <br>
      <button type="submit" class="abobox">Eintragen</button>
      </div>
      </form>
      <br>
      
      <?
      // Suche
      ?>
      <form method="post" action="#" onsubmit="return suchen();">
      Suche (mit Google):
      <br>
      <div style="text-align:center;">
      <input type="text" id="query" name="query" class="abobox">
      <br>
      <button type="submit" class="abobox">Suchen</button>
      </div>
      </form>
      <script type="text/javascript">
      function suchen() {
        var qy = this.query.value;
        var link = 'https://www.google.de/search?hl=de&as_q=' + qy + '&as_qdr=all&as_sitesearch=www.fs-blog.de&as_occt=any&safe=images';
        window.open(link);
        return false;
      }
      </script>
      <br>
      
      <?
      // Über das FS-Blog
      echo '<a href="'.BASEURL.'about.php">Über das FS-Blog</a>'
      ?>
    </div>
    </div>
    </div><!-- lt2 -->
    
    <div class="lt3">
    <div class="foot">
    <a href="<?=BASEURL?>kontakt.php">Kontakt</a>
    &nbsp; | &nbsp;
    <a href="<?=BASEURL?>about.php">Über das FS-Blog</a>
    &nbsp; | &nbsp;
    Erstellt mit <a href="http://jedit.org/" target="_blank">jedit</a>
    &nbsp; | &nbsp;
    <a href="<?=BASEURL?>rss.php">RSS-Feed</a>
    </div>
    </div>
    </body>
    </html>
    <?
  }

  
  protected function errmsg( $message ) {
    ?>
    <div style="position:absolute; left:300px; top:200px;">
    <table width="80%" align="center" border="2" bordercolor="#ff0000" cellpadding="5" bgcolor="#ccffff">
    <tr>
    <td width="100%" bgcolor="#ffcccc">
      <b>FEHLER:</b> 
      <br>
      <?=$message?>
      <br>
      <br>
      Bitte verständigen Sie den Admin.
    </td>
    </tr>
    </table>
    </div>
    <?
    $this->foot();
    die;
  }

}

