<?
/**
 * Database access
 */
require_once 'config.php';

abstract class DB {

  private $table = '';
  
  private $fields = '';
  
  
  /**
   * Get the DB connection handle
   */
  public function getPdo() {
    if (!isset($GLOBALS['pdo']) || !$GLOBALS['pdo']) {
      $GLOBALS['pdo'] = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
    }
    return $GLOBALS['pdo'];
  }
   
  /**
   * Constructor needs a table name
   */
  public function __construct($table, $fields = array()) {
    if (!$table = strtolower(trim($table))) {
      throw new Exception('No table name given');
    }
    $this->table = $table;
    $this->fields = $fields;
  }
  
  /**
   * Function for checking input fields
   */
  protected function checkField($pms, $fieldname, $bMustHaveValue = false) {
    if (!isset($this->fields[$fieldname])) {
      throw new \Exception('Tabelle '.$this->table.' hat laut D-Klasse keine Spalte '.$fieldname);
    }
    if (!isset($pms[$fieldname])) {
      throw new \Exception('Spalte '.$fieldname.' fehlt');
    }
    $val = trim($pms[$fieldname]);
    if ($bMustHaveValue) {
      switch ($this->fields[$fieldname]) {
      case 'int':
        if (!$val = (int) $val) {
          throw new \Exception($fieldname.' == 0');
        }
        break;
        
      case 'string':
        if (!strlen($val)) {
          throw new \Exception($fieldname.' ist leer');
        }
        break;
        
      default:
        throw new \Exception('Ungültiger typ für '.$fieldname.': '.$this->fields[$fieldname]);
      }
    }
    return $val;
  }
  
  
  /**
   * Create a row with database default values
   */
  public function create() {
    // create row
    $sql = "INSERT INTO ".$this->table."() VALUES()";
    $q = $this->getPdo()->prepare($sql);
    if (!$q->execute()) {
      throw new Exception('Fehler bei '.$sql);
    }
    
    // return new id
    $id = $this->getPdo()->lastInsertId();
    return $id;
  }
  
  
  /**
   * Create a row with given values
   * @param array $pms
   *  All fields (apart from id, of course) must be given
   */
  public function createValues($pms) {
    // checks
    if (!count($this->fields)) {
      throw new Exception('No fields given');
    }
    foreach ($this->fields as $field => $type) {
      if (!isset($pms[$field])) {
        throw new Exception('No value for '.$this->table.'.'.$field.' given.');
      }
    }
    
    // go
    $fieldnames = array_keys($this->fields);
    $sql = "INSERT INTO ".$this->table." ("
      .implode(', ', $fieldnames)
      .") VALUES(:"
      .implode(', :', $fieldnames)
      .")"
    ;
    $pm_db = array();
    foreach ($this->fields as $field => $type) {
      $pm_db[':'.$field] = $pms[$field];
    }
    $query = $this->getPdo()->prepare($sql);
    if (!$query->execute($pms)) {
      throw new Exception('Fehler bei '.$sql);
    }

    // return new id
    $id = $this->getPdo()->lastInsertId();
    return $id;
  }
  
  
  /**
   * Delete a row
   */
  public function delete($id) {
    // checks
    if (!$id = (int) $id) {
      throw new Exception('No id given.');
    }
    
    // go
    $sql = "DELETE FROM ".$this->table." WHERE id=:id";
    $q = $this->getPdo()->prepare($sql);
    if (!$q->execute(array(':id' => $id))) {
      throw new Exception('Fehler bei '.$sql);
    }
  }
    
  
  /**
   * Update all(!) fields in a row
   */
  public function edit($row) {
    // checks
    if (!isset($row['id']) || !$id = (int) $row['id']) {
      throw new Exception('No valid id given.');
    }
    if (!count($this->fields)) {
      throw new Exception('No fields given');
    }
    foreach ($this->fields as $field => $type) {
      if (!isset($row[$field])) {
        throw new Exception('No value for '.$this->table.'.'.$field.' given.');
      }
    }
    
    // prepare sql
    $sql = "UPDATE ".$this->table." SET ";
    $aSets = array();
    foreach ($this->fields as $field => $type) {
      $aSets[] = $field."=:".$field;
    }
    $sets = implode(', ', $aSets);
    $sql .= $sets;
    $sql .= " WHERE id=:id";
    $query = $this->getPdo()->prepare($sql);
    
    // prepare values
    $pms = array();
    foreach ($this->fields as $field => $type) {
      $pms[':'.$field] = $row[$field];
    }
    $pms[':id'] = $row['id'];
    
    // go
    if (!$query->execute($pms)) {
      throw new Exception('Fehler bei '.$sql);
    }
  }
    
  
  /**
   * update 1 field
   * @param int $id
   * @param string $field
   * @param mixed $val = the new value of the field in row with id=$id
   */
  public function setField($id, $field, $val) {
    if (!$id = (int) $id) {
      throw new Exception('Keine ID angegeben');
    }
    if (!$field = trim($field)) {
      throw new Exception('Keine Spalte angegeben');
    }
    $q = $this->getPdo()->prepare("UPDATE ".$this->table." SET ".$field."=:val WHERE id=:id");
    if (!$q->execute(array(':id' => $id, ':val' => $val))) {
      throw new Exception('Fehler bei update von '.$this->table);
    }
  }
    
  
  /**
   * Get one field of one row
   * @param int $id = Wert der ID-Spalte
   * @param string $fname = Name der Spalte
   */
  public function getField($id, $fname) {
    // checks
    if (!$id = (int) $id) {
      throw new Exception('No id given.');
    }
    
    // go
    $sql = "SELECT ".$fname." FROM ".$this->table." WHERE id=:id";
    $q = $this->getPdo()->prepare($sql);
    if (!$q->execute(array(':id' => $id))) {
      throw new Exception('Fehler bei '.$sql);
    }
    $row = $q->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
      throw new Exception('Kein Datensatz Nr. '.$id.' in '.$this->table);
    }
    
    return $row[$fname];
  }
    
  
  /**
   * Get a row
   */
  public function getRow($id) {
    // checks
    if (!$id = (int) $id) {
      throw new Exception('No id given.');
    }
    
    // go
    $sql = "SELECT * FROM ".$this->table." WHERE id=:id";
    $q = $this->getPdo()->prepare($sql);
    if (!$q->execute(array(':id' => $id))) {
      throw new Exception('Fehler bei '.$sql);
    }
    $row = $q->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
      throw new Exception('Kein Datensatz Nr. '.$id.' in '.$this->table);
    }
    
    return $row;
  }
  
  
  /**
   * Get all (select *), neuestes zuerst
   */
  public function getAll() {
    $sql = "SELECT * FROM ".$this->table
      ." ORDER BY id DESC"
    ;
    $q = $this->getPdo()->prepare($sql);
    if (!$q->execute()) {
      throw new Exception('Fehler bei '.$sql);
    }
    $res = $q->fetchAll(PDO::FETCH_ASSOC);
    return $res;
  }
  
}

