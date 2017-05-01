<?
require_once PATH_PRIVATE.'V/View.php';

class VRedirect extends View {

  public function display($errmsg, $vdata) {
    if ($errmsg) {
      echo $errmsg;
      exit;
    }
    
    // ok
    $loc = trim($vdata);
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: ".$loc);
    header("Connection: close");
  }

}
