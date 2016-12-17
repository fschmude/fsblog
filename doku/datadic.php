<?
/**
 * Ein create-skript für die ganze Datenbank erzeugen
 */
ini_set('display_errors', true);
 
// Dies ist das komplette data dictionary
$database = array(
  array('name' => 'artikel',
    'fields' => array(
      array('titel', 'char', 70),
      array('url', 'char', 100),
      array('metadesc', 'char', 156),
      array('datum', 'date'),
      array('text', 'text'),
      array('status', 'int', 1)
  )),
  array('name' => 'bilder',
    'fields' => array(
      array('width', 'int', 4),
      array('height', 'int', 4),
      array('ext', 'char', 3),
      array('url', 'char', 255),
      array('alt', 'char', 255)
  )),
  array('name' => 'leser',
    'fields' => array(
      array('lmail', 'char', 255),
      array('datum', 'date'),
      array('code', 'char', 15),
      array('status', 'int', 1)
  )),
  array('name' => 'posts',
    'fields' => array(
      array('aid', 'int', 11),
      array('lfnr', 'int', 11),
      array('code', 'char', 15),
      array('username', 'char', 255),
      array('usermail', 'char', 255),
      array('datum', 'date'),
      array('text', 'text'),
      array('status', 'int', 1)
  )),
  array('name' => 'snips',
    'fields' => array(
      array('datum', 'date'),
      array('text', 'text'),
      array('fbid', 'char', 16)
  )),
  array('name' => 'videos',
    'fields' => array(
      array('width', 'int', 4),
      array('height', 'int', 4),
      array('vname', 'char', 250)
  ))
);

$br = '<br>'."\n";

// los geht's, ausgeben
foreach ($database as $table) {
  echo 'CREATE TABLE '.$table['name'].' ('.$br
    .'id int(11) NOT NULL AUTO_INCREMENT'
  ;
  foreach ($table['fields'] as $field) {
    $fieldline = ','.$br.$field[0];
    switch ($field[1]) {
    case 'char':
      $fieldline .= ' varchar('.$field[2].') NOT NULL DEFAULT \'\'';
      break;
      
    case 'date':
      // einziger Datentyp, bei dem NULL sinnvoll ist
      $fieldline .= ' datetime DEFAULT NULL';
      break;
      
    case 'text':
      // ist in MySQL per default eh immer NULL und andere defaults gehen nicht!
      $fieldline .= ' text';
      break;
      
    case 'int':
      $length = isset($field[2]) && $field[2] ? (int) $field[2] : 0;
      $fieldline .= ' int('.$length.') NOT NULL DEFAULT 0';
      break;
      
    default:
      echo 'kein gültiger Typ: '.$field[1];
    }
    echo $fieldline;
  }
  
  // table statement schließen
  echo ','.$br.'PRIMARY KEY (id)'.$br;
  echo ');'.$br.$br;
}

