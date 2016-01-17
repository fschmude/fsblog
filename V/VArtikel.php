<?
require_once 'V/View.php';

class VArtikel extends View {

  public function display($errmsg, $ddata) {
    try {
      $art = $ddata['artikel'];
      $canonical = BASEURL.'artikel/'.$art['url'].'.htm';
      $this->head($art['titel'], $canonical, $art['datum'], $art['metadesc']);
      $text_html = $this->parse_artikel($art['text'], $art['bilder']);
      
    } catch (Exception $e) {
      $errmsg = $e->getMessage();
    }
    
    if ($errmsg) {
      $this->errmsg($errmsg);
      
    } else {
      echo $text_html;
    }
    
    // Social Media Links
    ?>
    <table style="width:100%;text-align:right;border:0;">
    <tr>
    <td style="width:60%">&nbsp;</td>
    <td style="width:20%">
    <!-- google plus -->
    <div><g:plusone></g:plusone></div>
    <script type="text/javascript">
    window.___gcfg = {
      lang: 'en-US'
    };
    (function() {
        var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
        po.src = 'https://apis.google.com/js/plusone.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
    })();
    </script>
    
    </td>
    <td style="width:20%">
    <?
    // Fratzenbuch
    echo '<div class="fb-like" data-href="'.$canonical.'" data-send="false" data-layout="button_count" data-width="200" data-show-faces="true" data-font="arial"></div>';
    ?>
    </td></tr></table>
    <?

    // Postings
    if ($anz_posts = count($art['posts'])) {
      ?>
      <br><br>
      <div class="us">Kommentare</div>
      <?
      foreach ($art['posts'] as $post) {
        ?>
        <div style="margin:0px;border-color:#009999; border-width:1px; border-style:solid; padding:5px;">
          <table style="border-width:0px; width:100%; margin:0;padding:0">
          <tr>
          <td style="padding:0px;">
            #<?= $post['lfnr'] ?> von "<?= $post['username'] ?>":
          </td>
          <td style="padding:0px;text-align:right;">
            <?= Date('Y-m-d H:i', strtotime($post['datum'])) ?>
          </td>
          </tr>
          </table>
          <?= $this->parse_post($post['text']) ?>
        </div>
        <div style="height:4px"></div>
        <?
      }
      if ($anz_posts == 1) {
        echo '1 Kommentar.';
      } else {
        echo $anz_posts.' Kommentare.';
      }
      echo '<br><br>'."\n";
    }
    ?>
    
    Mein Kommentar dazu:
    <form method="post" action="../confirm.php">
    <input type="hidden" name="aid" value="<?= $art['id'] ?>">
    <div style="border-color:#009999; border-width:1px; border-style:solid; padding:10px; text-align:center;">
    <table style="margin:0px;padding:0px;border-spacing:0px;border-collapse:collapse; border-width:0px;width:100%">
    <tr>
    <td style="width:30%;text-align:right;">
      Name:
    </td>
    <td style="width:70%;text-align:left;">
      <input type="text" name="username" value="">
      (freiwillige Angabe)
    </td>
    </tr>
    <tr>
    <td style="text-align:right;">
      E-Mail-Adresse:
    </td>
    <td style="text-align:left;">
      <input type="email" name="usermail" value="@">
      (Wird nirgends veröffentlicht, ist aber Pflichtangabe)
    </td>
    </tr>                                                               
    </table>
    <br>
    Kommentar:
    <br>
    <textarea name="ptext" value="" rows="8" style="width:90%;"></textarea>
    <br>
    <input type="submit" value="Kommentar abschicken">
    </div>
    </form>
    <?

    $this->foot($ddata['navi_arts']);
  }

  
  /**
   * Artikel für die Web-Anzeige parsen
   */
  private function parse_artikel($text, $bilder) {
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
        if ($bild['url']) {
          $url = $bild['url'];
        } else {
          $url = $bild['id'];
        }
        $repl =
          '<div align="center">'
          .'<img src="'.BASEURL.'imga/'.$url.'.'.$bild['ext'].'" width="'.$bild['width'].'" height="'.$bild['height'].'" alt="'.$bild['alt'].'">'
          .'</div>'
        ;
        $text = str_replace($search, $repl, $text);
      }
    }
    
    return $text;
  }

  
  /**
   * Parsing von User-Kommentaren: Kein HTML erlaubt.
   */
  private function parse_post( $text ) {
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

