<?
require_once 'V/View.php';

class VStatic extends View {

  public function display($errmsg, $naviarts, $page) {
    try {
      switch ($page) {
      case 'about':
        $titel = 'Über das FS-Blog';
        $text = '"FS", das steht natürlich für den "Freisinn", eine liberale Wählervereinigung, die der schwarzbraune Widerling in Heinrich Manns "Untertan" so sehr hasst, dass er sich zu ihrer Bekämpfung mit dem roten Widerling verbündet.'
          .'<br><br>'."\n"
          .'Und da sind wir auch schon beim Thema.'
          .'<br>'."\n"
          .'In der heutigen geistigen Landschaft Europas ist die Freude an der Gedankenfreiheit so dermaßen unter die Räder gekommen, dass für die Wenigen, die sich wenigstens bemühen, nicht der sozialistischen oder der konservativen Meute anzugehören, nur noch das Internet übrig bleibt.'
          .'<br>'."\n"
          .'Ob es dort möglich ist, der allgemeinen Verteufelung alles "neoliberalen" eine Stimme der Vernunft, eine Stimme für die Freiheit entgegenzusetzen?'
          .'<br>'."\n"
          .'Noch gilt das Grundgesetz von 1949, und laut diesem sollte es möglich sein.'
          .'<br><br>'."\n"
          .'"Freisinn"? Nicht doch.'
          .'<br>'."\n"
          .'Ich heiße Fritz Schmude, bin Jahrgang 1969, in der West-BRD aufgewachsen und verdiene jetzt meine Brötchen als Programmierer in München.'
          .'<br>'."\n"
          .'Ich bin identisch mit dem Poster "Birne" bei Telepolis von 2000 bis 2012 und bei <a href="http://www.islam-deutschland.info/" target="_blank">Islam-Deutschland.info</a> von 2006 bis 2010.'
          .'<br>'."\n"
          .'Mehr braucht man nicht zu wissen, um hier mitzudiskutieren, wobei ich viel Spaß wünsche.'
          .'<br>'."\n"
          .'Meinungsaustausch sollte immer Spaß machen!'
          .'<br><br>'."\n"
          .'Brian: "Aber lauft doch nicht mir hinterher, Ihr seid doch alle Individuen!"'
          .'<br>'."\n"
          .'Die Masse, im Chor: "Jawohl, wir sind alle Individuen!"'
          .'<br>'."\n"
          .'(Aus dem Film <a href="http://de.wikipedia.org/wiki/Life_of_Brian" target="_blank">Das Leben des Brian</a>)'
          .'<br><br>'."\n"
          .'Bis demnächst, fs.'
        ;
        break;
        
      case 'kontakt':
        $titel = 'Kontakt zu fs';
        $text = 'Wenn Sie mir persönlich etwas mitteilen möchten, so mailen Sie bitte an:'
          .'<br><br>'."\n"
          .'<b>mail at fs-blog.de</b>'
        ;
        break;
        
      default:
        throw new Exception('Ungültige page "'.$page.'"');
      }
      
      // page is ok, so canonical must be, too
      $canonical = BASEURL.$page.'.php';
      
      $this->head($titel, $canonical, '', $titel, $naviarts);
      
    } catch (Exception $e) {
      $errmsg = $e->getMessage();
    }
    
    // now echo everything
    if ($errmsg) {
      $this->errmsg($errmsg);
    } else {
      echo $text;
    }

    $this->foot();              
  }
  
}