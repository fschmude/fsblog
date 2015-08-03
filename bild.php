<?
/**
 * Auslieferung eines Bildes
 */
require_once 'config.php';
require_once 'C/CBild.php';

$c = new CBild();
$c->run($_GET, $_POST, $_FILES);

