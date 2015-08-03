<?
require_once 'V/View.php';

class VListe extends View {

  public function display($errmsg, $arts, $page) {
    try {
      switch ($page) {
      case 'index':
        $titel = 'Willkommen';
        $desc = 'Ein freisinniges Blog. Der allgemeinen Verteufelung alles Liberalen eine Stimme der Vernunft, eine Stimme für die Freiheit entgegensetzen.';
        $vorher = 'Willkommen auf dem FS-Blog. Viel Spaß beim Lesen und Kommentieren.'
          .'<br><br>'."\n"
          .'Bevor Sie sich in den Kampf stürzen, können Sie auch noch etwas zu'  
          .' <a href="about.php">Sinn und Unsinn des FS-Blog</a> lesen.'
          .'<br><br>'."\n"
          .'Nun aber los.'
          .'<br><br>'."\n"
          .'<h2>Neue Artikel</h2>'
        ;
        $nachher = '<h2>Ältere Artikel</h2>'
          .'Eine Suche gibt es noch nicht, aber selbstverständlich eine' 
          .' <a href="alle.php">Liste aller Artikel</a>.'
          .'<br><br>'."\n"
        ;
        break;
        
      case 'alle':
        $titel = 'Alle Artikel';
        $desc = 'Alle bisher erschienenen Artikel des FS-Blog';
        $vorher = 'Hier ist alles bisher geschriebene, nach Erscheinungsdatum geordnet.'
          .'<br><br>'."\n"
        ;
        $nachher = 'Das war\'s bis jetzt.';
        break;
        
      default:
        throw new Exception('Ungültige page: "'.$page.'"');
      }
      
      // page is ok, so canonical must be, too
      $canonical = BASEURL.$page.'.php';
      
      $naviarts = array();
      $i = 0;
      foreach ($arts as $art) {
        if ($i<3) {
          $naviarts[] = $art;
        }
        $i++;
      }
      $this->head($titel, $canonical, '', $desc);
      
    } catch (Exception $e) {
      $errmsg = $e->getMessage();
    }
    
    // now echo everything
    if ($errmsg) {
      $this->errmsg($errmsg);
    } else {
      echo $vorher;
      //echo '<br><br>'."\n";
      foreach ($arts as $art) {
        echo Date('Y-m-d', strtotime($art['datum'])).': '."\n";
        $url = BASEURL.'artikel/'.$art['url'].'.htm';
        echo '<a href="'.$url.'">'.$art['titel'].'</a><br>'."\n";
        $text = $this->parse_txt($art['text']);
        $text = substr($text, 0, 400);
        echo $text.'... <a href="'.$url.'">Weiterlesen</a>';
        echo '<br><br>'."\n";
      }
      echo $nachher;
    }

    $this->foot($naviarts);
  }

  
  /**
   * txt-only-parsing für Artikelliste, description-metatag, index usw.
   */
  private function parse_txt( $text ) {
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
  
}

