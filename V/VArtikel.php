<?
require_once 'V/View.php';

class VArtikel extends View {

  /**
   * @param string $errmsg
   * @param array $data = array(
   *    'type' => artikel / monat / snippet
   *    'id' => 52, (nur bei Artikeln und Snippets)
   *    'titel' => 'Links von März',
   *    'url' => 'http://...komplett...',
   *    'metadesc' => 'Links und Kommentare vom März 2016',
   *    'datum' => '2016-04-03 11:50:17',
   *    'text' => noch nicht nach Html geparst,
   *    'bilder' => array, indices sind die IDs der gezogenen Bilder,
   *    'vids' => dito,
   *    'posts' => array der posts, (vorerst nur bei Artikeln)
   *    'urlFB' => Facebook-URL (nur bei snippets),
   *    'urlAfter'  => Komplett-URL des späteren Eintrags (nur bei snippets),
   *    'urlBefore' => Komplett-URL des vorigen Eintrags (nur bei snippets),
   *    'urlMonat' => Komplett-URL des vorigen Eintrags (nur bei snippets),
   *    'navi_arts' => array( array() )
   * );
   */
  public function display($errmsg, $data) {
    if ($errmsg) {
      $this->errmsg($errmsg);
    }
    
    try {
      if (!in_array($data['type'], array('artikel', 'monat', 'snippet'))) {
        throw new Exception('Unbekannter Typ: "'.$data['type'].'"');
      }
      
      // robots: Monatsliste nicht in Google aufnehmen, sondern nur Schnippel und Artikel
      $robots = $data['type'] == 'monat' ? 'noindex, follow' : 'index, follow';
      
      $this->head($data['titel'], $data['url'], $data['datum'], $data['metadesc'], $robots);
      $text_html = $this->parseArtikel($data['text'], $data['bilder'], $data['vids']);
      
    } catch (Exception $e) {
      $errmsg = $e->getMessage();
    }
    
    if ($errmsg) {
      $this->errmsg($errmsg);
    }
    
    echo $text_html;
    
    // Links für snippets
    if ($data['type'] == 'snippet') {
      echo '<br><br>'."\n";
      if (isset($data['urlFB']) && $data['urlFB']) {
        echo '<a href="'.$data['urlFB'].'" target="_blank">Diesen Eintrag bei Facebook kommentieren</a><br><br>'."\n";
      }
      if (isset($data['urlBefore']) && $data['urlBefore']) {
        echo '<a href="'.$data['urlBefore'].'">Voriger Eintrag</a> &lt;- &nbsp; ';
      }
      echo ' <a href="'.$data['urlMonat'].'">Ganzen Monat zeigen</a> ';
      if (isset($data['urlAfter']) && $data['urlAfter']) {
        echo ' &nbsp; -&gt; <a href="'.$data['urlAfter'].'">Nächster Eintrag</a>';
      }
      echo '<br>'."\n";
    }

    // Postings
    if ($data['type'] == 'artikel') {
      if ($anz_posts = count($data['posts'])) {
        ?>
        <br><br>
        <div class="us">Kommentare</div>
        <?
        foreach ($data['posts'] as $post) {
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
      <input type="hidden" name="aid" value="<?= $data['id'] ?>">
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
    }
    
    $this->foot($data['navi_arts']);
  }

  
  /**
   * Artikel für die Web-Anzeige parsen
   * @access public for tests only
   */
  public function parseArtikel($text, $bilder, $videos) {
    // Zeilenumbrüche
    $text = $this->parseRN($text);
    
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
          '<div align="center">'."\n"
          .'<img src="'.BASEURL.'imga/'.$url.'.'.$bild['ext'].'" width="'.$bild['width'].'" height="'.$bild['height'].'" alt="'.$bild['alt'].'">'."\n"
          .'</div>'."\n"
        ;
        $text = str_replace($search, $repl, $text);
      }
    }
    
    // Videos
    if (count($videos)) {
      foreach ($videos as $video) {
        $search = '<video id="'.$video['id'].'">';
        $pos = strpos($text, $search);
        
        // Was soll angezeigt werden
        $sources = (int) $video['sources'];
        if (!$sources) {
          $repl = '[Keine Video-Dateien zu "'.$video['vname'].'" gefunden.]<br>'."\n";
        } else {
          $repl = '<video width="'.$video['width'].'" height="'.$video['height'].'" controls>'."\n";
          if ($sources == 1 || $sources == 3) {
            $repl .= ' <source src="'.BASEURL.'imga/'.$video['vname'].'.mp4" type="video/mp4">'."\n";
          }
          if ($sources == 2 || $sources == 3) {
            $repl .= ' <source src="'.BASEURL.'imga/'.$video['vname'].'.ogg" type="video/ogg">'."\n";
          }
          $repl .= ' Ihr Browser unterstützt den Tag "video" nicht.'."\n"
            .'</video>'."\n"
          ;
        }
        
        // Jetzt Text einfügen
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
    $text = $this->parseRN( $text );
    return $text;
  }
  
  // Zeilenumbrüche
  private function parseRN( $text ) {
    $text = str_replace( "\r\n", "\n", $text );
    $text = str_replace( "\n", "\n<br>\n", $text );
    return $text;
  }

}

