<?
/**
 * SQLs for table videos
 */
require_once 'D/DB.php';

class DVideos extends DB {
  
  public function __construct() {
    parent::__construct('videos', array(
    ));
  }
  
}

