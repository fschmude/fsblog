<?
require_once PATH_PRIVATE.'V/View.php';

class VMailtext extends View {

  public function display($errmsg, $data) {
    if ($errmsg) {
      echo $errmsg;
      exit;
    }
    
    header("Content-Type: text/html; charset=utf-8");
    ?><!DOCTYPE HTML>
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta charset="UTF-8">
    <?
    echo '<title>Mailing: '.$data['titel'].'</title>'."\n";
    ?>
    </head>
    <body>
    von, an: mail@fritz-schmude.de
    <br>
    bcc: <?= $data['leser'] ?>
    <br>
    subject: Neuer Artikel auf fs-blog.de: <?= $data['titel'] ?>
    <br><br>
    
    Liebe Abonnenten,
    <br><br>
    
    es gibt einen neuen Artikel auf www.fs-blog.de:
    <br>
    &quot;<?= $data['titel'] ?>&quot;
    <br><br>
              
    Sie finden ihn unter dem Link
    <br>
    <?= $data['url'] ?>
    <br><br>
    
    Wenn Sie diese Mails nicht mehr erhalten wollen, antworten Sie einfach auf diese Mail und schreiben irgendwo in den Text "unsubscribe".
    <br><br>
    
    Viele Grüße
    <br><br>
    
    Fritz Schmude
    <br><br>
    
    </body>
    </html>
    <?
  }
}

