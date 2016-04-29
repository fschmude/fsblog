<?
require_once 'C/Controller.php';

class CArtikel extends Controller {
  
  public function work($get, $post, $files) {
    $errmsg = $vdata = '';
    try {
      $mart = $this->getObject('MArtikel');
      $view = $this->getObject('VArtikel');
      if (isset($get['url']) && strlen($fakeurl = trim($get['url']))) {
        // call by (faked) url?
        $vdata = $mart->getArtikelKomplettByUrl($fakeurl);
        $this->addNavi($vdata);

      } elseif (isset($get['monat']) && $monat = trim($get['monat'])) {
        // call by month
        $msnip = $this->getObject('MSnippet');
        $vdata = $msnip->getMonat($monat);
        $this->addNavi($vdata);
        
      } elseif (isset($get['sid']) && $sid = (int) $get['sid']) {
        // call by snippet id
        $msnip = $this->getObject('MSnippet');
        $vdata = $msnip->getSnippet($sid);
        $this->addNavi($vdata);
        
      } elseif (isset($get['aid']) && $aid = (int) $get['aid']) {
        // call by article id (still out there in the internet!)
        $vdata = $mart->completeUrl($mart->getUrl($aid));
        $view = $this->getObject('VRedirect');
        
      } else {
        throw new Exception('incorrect call');
      }

    } catch (Exception $e) {
      $errmsg = $this->handleError($e);
    }
  
    $view->display($errmsg, $vdata);
  }
 
  
  /**
   * Helper
   * @access public for testing only
   * @param ByRef array &$artikel
   */
  public function addNavi(&$artikel) {
    $mart = $this->getObject('MArtikel');
    $navi_arts = $mart->getTop(5);
    $artikel['navi_arts'] = $navi_arts;
  }
  
}

