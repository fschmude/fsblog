<?
/**
 * Auslieferung eines Bildes
 */
require_once 'path2private.php';
require_once PATH_PRIVATE.'C/CBild.php';

$c = new CBild();
$c->work($_GET, $_POST, $_FILES);

