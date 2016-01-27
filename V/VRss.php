<?
require_once 'V/View.php';

class VRss extends View {

  public function display($errmsg, $arts) {
    if ($errmsg) {
      echo $errmsg;
      exit;
    }
    
    header( 'Content-type: application/rss+xml' );
    echo '<?xml version="1.0" encoding="utf-8"?>';
    ?>
    <rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
    <title>FS-Blog</title>
    <link>http://www.fs-blog.de/</link>
    <atom:link href="http://www.fs-blog.de/rss.php" rel="self" type="application/rss+xml" />
    <description>Ein freisinniges Blog.</description>
    <language>de-de</language>
    <copyright>fs-blog.de</copyright>
    <?
    echo '<pubDate>'.Date( 'D, d M Y H:i:s O' ).'</pubDate>';
    /* todo
        <image>
          <url>URL einer einzubindenden Grafik</url>
          <title>Bildtitel</title>
          <link>URL, mit der das Bild verknuepft ist</link>
        </image>
    */
    foreach ($arts as $art) {
      $teaser = substr($art['text'], 0, 400);
      echo '<item>'."\n";
      echo '<title>'.$art['titel'].'</title>'."\n";
      echo '<description><![CDATA['.$teaser.'...]]></description>'."\n";
      $url = $this->completeUrl($art['url']);
      echo '<link>'.$url.'</link>'."\n";
      echo '<guid>'.$url.'</guid>'."\n";
      echo '<author>mail@fs-blog.de (fs)</author>'."\n";
      echo '<pubDate>'.Date( 'D, d M Y H:i:s O', strtotime($art['datum']) ).'</pubDate>'."\n";
      echo '</item>'."\n";
      echo "\n";
    }
    ?> 
    </channel>
    </rss>
    <?
  }

}

