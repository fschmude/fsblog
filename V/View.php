<?
class View {
  
  protected $hinweis;
  
  /**
   * Constructor
   */
  public function __construct() {
    $this->hinweis = 'VERSION = '.VERSION;
  }

  public function head( $titel, $canonical = '', $datum = '', $desc = '', $navi_arts ) {
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
    <table style="width:100%;margin:0px;padding:0px;border-spacing:0px;border-collapse:collapse; background-color: #ffffff;">
    <tr>
    <td class="icon">
    <?
    echo '<a href="'.BASEURL.'index.php"><img src="'.BASEURL.'img/fslogo.png" style="border-width:0px;"></a>'."\n";
    ?>
    </td>
    <td class="header">
    <h1>FS-Blog</h1>
    Das freisinnige Blog von Fritz Schmude.
    </td>
    </tr>
    
    <tr>
    <td class="navi">
      <?
      // Link zu START
      echo '<a href="'.BASEURL.'index.php">Start</a>'."\n";
      echo '<br><br>'."\n";
      
      // Artikelliste
      echo 'Neuere Artikel:<br>'."\n";
      foreach ($navi_arts as $art) {
        echo '<a href="'.BASEURL.'artikel/'.$art['url'].'.htm">'.Date('Y-m-d', strtotime($art['datum'])).':<br>'.$art['titel'].'</a>';
        echo '<br><br>'."\n";
      }
      
      echo '<a href="'.BASEURL.'alle.php">Alle Artikel</a>';
      echo '<br><br>'."\n";
      
      // Mail-Feature
      ?>
      <form method="post" action="confirm.php">
      Ich will bei neuen Artikeln informiert werden:
      <br>
      (E-Mail-Adresse eingeben)
      <br>
      <input type="email" id="lmail" name="lmail">
      <br>
      <div align="center">
      <button type="submit">Eintragen</button>
      </div>
      </form>
      <br>
      
      <?
      // Über das FS-Blog
      echo '<a href="'.BASEURL.'about.php">Über das FS-Blog</a>'
      ?>
    </td>
    <td class="content">
      <table style="width:100%;margin:0px;padding:0px;border-spacing:0px;border-collapse:collapse; background-color: #ffffff;">
      <tr>
      <td width="50%">
        <h2><?=$titel?></h2>
      </td>
      <td class="normal" style="text-align:right">
        <?=$datum?>
      </td>
      </tr>
      </table>
      <div>
    <?
  }

  public function foot(){
    ?>
      </div>
    </td>
    </tr>
    </table>
    
    <div class="foot">
    <a href="<?=BASEURL?>kontakt.php">Kontakt</a>
    &nbsp; | &nbsp;
    Erstellt mit <a href="http://jedit.org/" target="_blank">jedit</a>
    &nbsp; | &nbsp;
    <a href="<?=BASEURL?>rss.php">RSS-Feed</a>
    </div>
    </body>
    </html>
    <?
  }

  public function errmsg( $message ) {
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

  /**
   * Artikel für die Web-Anzeige parsen
   */
  public function parse_artikel($text, $bilder) {
    // Zeilenumbrüche
    $text = $this->parse_rn($text);
    
    // Wiki-Links
    $text = str_replace( '<wiki href="', '<a target="_blank" href="http://de.wikipedia.org/wiki/', $text );
    $text = str_replace( '</wiki>', '</a>', $text );
    
    // Bilder
    if (count($bilder)) {
      foreach ($bilder as $bild) {
        $search = '<imga id="'.$bild['id'].'">';
        $pos = strpos($text, $search);
        $repl =
          '<div align="center">'
          .'<img src="'.BASEURL.'imga/'.$bild['id'].'.'.$bild['ext'].'" width="'.$bild['width'].'" height="'.$bild['height'].'" align="center">'
          .'</div>'
        ;
        $text = str_replace($search, $repl, $text);
      }
    }
    
    return $text;
  }
  
  /**
   * txt-only-parsing für Artikelliste, description-metatag, index usw.
   */
  public function parse_txt( $text ) {
    $text = $this->cut_opening_tags( $text, 'a' );
    $text = str_replace( '</a>', '', $text );
    $text = $this->cut_opening_tags( $text, 'wiki' );
    $text = str_replace( '</wiki>', '', $text );
    $text = $this->cut_opening_tags( $text, 'img' ); // sollte auch imga erwischen
    $text = str_replace( '<h2>', '', $text );
    $text = str_replace( '</h2>', '', $text );
    return $text;
  }
  
  private function cut_opening_tags( $text, $tag ) {
    $pos_a = strpos( $text, '<'.$tag );
    while ($pos_a !== false) {
      $pos_e = strpos( $text, '>', $pos_a );
      if ($pos_e) {
        $tag_complete = substr( $text, $pos_a, $pos_e - $pos_a + 1 );
        $text = str_replace( $tag_complete, '', $text );
        $pos_a = strpos( $text, '<'.$tag, (int) $pos_a );
      } else {
        // opening tag endet nicht (z.B. weil zu lang für Teaser), alles abschneiden
        $text = substr($text, 0, $pos_a);
        $pos_a = false;
      }
    }
    return $text;
  }
  
  /**
   * Parsing von User-Kommentaren: Kein HTML erlaubt.
   */
  public function parse_post( $text ) {
    $text = str_replace( '<', '&lt;', $text );
    $text = str_replace( '>', '&gt;', $text );
    $text = $this->parse_rn( $text );
    return $text;
  }
  
  // Zeilenumbrüche
  private function parse_rn( $text ) {
    $text = str_replace( "\r\n", "\n", $text );
    $text = str_replace( "\n", "\n<br>\n", $text );
    return $text;
  }

}
