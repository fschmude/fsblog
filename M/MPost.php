<?
require_once 'M/Model.php';

class MPost extends Model {
  
  /**
   * Posting bestätigen
   */
  public function confirm($code) {
    // code suchen
    $post = $this->dobj->getRowByCode($code);
    if (!isset($leser['lmail'])) {
      throw new Exception('Es existiert kein Eintrag mit code="'.$code.'"');
    }
    switch ($leser['status']) {
    case 0:
      // freischalten
      $this->dobj->setField($leser['id'], 'status', 1);
      break;
      
    case 1:
      // schon freigeschaltet, Doppelklick?
      break;
      
    default:
      throw new Exception('Ungültiger Status: '.$leser['status']);
    }

    return $leser['lmail'];
  }

}

