<?
require_once 'V/IView.php';

abstract class View implements IView {
  
  protected $hinweis;
  
  /**
   * Constructor
   */
  public function __construct() {
    $this->hinweis = 'VERSION = '.VERSION;
  }

  protected function head($titel, $canonical = '', $datum = '', $desc = '') {
    header("Content-Type: text/html; charset=utf-8");
    ?><!DOCTYPE HTML>
    <html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml"> 
    <head>
    <meta charset="UTF-8">
    <?
    echo '<!-- '.$this->hinweis.' -->'."\n";
    echo '<title>FS: '.$titel.'</title>'."\n";
    echo '<link href="'.BASEURL.'img/styles.css" type="text/css" rel="stylesheet">'."\n";
    echo '<link rel="SHORTCUT ICON" href="'.BASEURL.'img/favicon.ico">'."\n";
    if ($desc) {
      echo '<meta name="description" content="'.$desc.'">'."\n";                                                                                             
    }
    if ($canonical) {
      echo '<link rel="canonical" href="'.$canonical.'" />'."\n";
    }
    echo '<link rel="alternate" type="application/rss+xml" title="RSS" href="'.BASEURL.'rss.php" />'."\n";
    ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>                                                   
    <?
    if ($canonical) {
      ?>
      <!-- Fratzenbuch -->
      <div id="fb-root"></div>
      <script>
      (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/de_DE/all.js#xfbml=1";
        fjs.parentNode.insertBefore(js, fjs);
      }(document, 'script', 'facebook-jssdk'));
      </script>
      <?
    }          
    ?>
    <div class="lt1">
    <div class="icon">
    <br>
    <?
    echo '<a href="'.BASEURL.'index.php"><img src="'.BASEURL.'img/fslogo.png" style="border-width:0px;"></a>'."\n";
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
      echo '<a href="'.BASEURL.'index.php">Start</a>'."\n";
      echo '<br><br>'."\n";
      
      // Artikelliste
      echo 'Neuere Artikel:<br>'."\n";
      foreach ($navi_arts as $art) {
        echo '<a href="'.$this->completeUrl($art['url']).'">'.Date('Y-m-d', strtotime($art['datum'])).':<br>'.$art['titel'].'</a>';
        echo '<br><br>'."\n";
      }
      
      echo '<a href="'.BASEURL.'alle.php">Alle Artikel</a>';
      echo '<br><br>'."\n";
      
      // Mail-Feature
      ?>
      <form method="post" action="<?= BASEURL ?>confirm.php">
      Ich will bei neuen Artikeln informiert werden:
      <br>
      (E-Mail-Adresse eingeben)
      <br>
      <div style="text-align:center;">
      <input type="email" id="lmail" name="lmail" class="abobox">
      <br>
      <button type="submit" class="abobox">Eintragen</button>
      </div>
      </form>
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
    Erstellt mit <a href="http://jedit.org/" target="_blank">jedit</a>
    &nbsp; | &nbsp;
    <a href="<?=BASEURL?>rss.php">RSS-Feed</a>
    </div>
    </div>
    </body>
    </html>
    <?
  }

  
  /** 
   * @param string $url = titel
   * @return string http:/...titel.htm
   */
  protected function completeUrl($url) {
    return BASEURL.'artikel/'.$url.'.htm';
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

