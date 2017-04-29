<?
/**
 * SQLs for table posts
 */
require_once PATH_PRIVATE.'D/DB.php';

class DPosts extends DB {
  
  public function __construct() {
    parent::__construct('posts', array(
      'aid' => 'int',
      'lfnr' => 'int',
      'code' => 'string',
      'username' => 'string',
      'usermail' => 'string',
      'text' => 'string',
      'datum' => 'date',
      'status' => 'int'
    ));
  }
  

  /**
   * Get published posts for 1 artikel
   */
  public function getPostsForAid($aid) {
    $st_p = $this->getPdo()->prepare(
      "SELECT * FROM posts"
      ." WHERE aid=:aid AND status=2"
      ." ORDER BY lfnr"
    );
    $st_p->bindParam(':aid', $aid);
    if (!$st_p->execute()) {
      throw new Exception('Fehler beim Holen der Kommentare zu aid='.$aid);
    }
    $posts = $st_p->fetchAll(PDO::FETCH_ASSOC);
    
    return $posts;
  }


  /**
   * Höchste Lfnr der Postings zu einem Artikel
   */
  public function getMaxLfnr($aid) {
    if (!$aid = (int) $aid) {
      throw new Exception('No valid article id given');
    }
    
    $stmt = $this->getPdo()->prepare( "SELECT max(lfnr) lfnr FROM posts WHERE aid=:aid" );
    $stmt->bindParam(':aid', $aid);
    if (!$stmt->execute()) {
      throw new Exception('Fehler beim Zählen der postings zu aid='.$aid);
    }
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $lfnr = isset($row['lfnr']) ? $row['lfnr'] : 0;
    return $lfnr;
  }
  
}

