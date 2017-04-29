<?
/**
 * CSitemap, Controllerklasse für eine Sitemap
 */
require_once PATH_PRIVATE.'C/Controller.php';

class CSitemap extends Controller {
  
  public function work($get, $post, $files) {
    try {
      echo '<?xml version="1.0" encoding="UTF-8"?>';
      ?>
      <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
      <?
      // Startseite
      $this->echoUrl(BASEURL);
      
      // Sonstige statische Seiten
      $this->echoUrl(BASEURL.'about.php');
      $this->echoUrl(BASEURL.'alle.php');
      $this->echoUrl(BASEURL.'kontakt.php');
      $this->echoUrl(BASEURL.'rss.php');
      
      // Artikel und Monate
      $mart = $this->getObject('MArtikel');
      $arts = $mart->getAllLive();
      foreach ($arts as $art) {
        $this->echoUrl($art['url']);
      }
      
      // alle einzelnen Schnippel
      $dsnip = $this->getObject('DSnips');
      $snipids = $dsnip->getAllIds();
      foreach ($snipids as $snipid) {
        $surl = $mart->completeUrl('', $snipid);
        $this->echoUrl($surl);
      }
      ?>
      </urlset>
      
      <?
      
    } catch (Exception $e) {
      $errmsg = $this->handleError($e);
      echo $errmsg;
    }
    
  }
 
  
  /**
   * Einen URL ausgeben
   */
  private function echoUrl($url) {
    echo '<url><loc>';
    echo $url;
    echo '</loc></url>'."\n";
  }
  
}
