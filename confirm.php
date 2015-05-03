<?
require_once 'C/CConfirm.php';
require_once 'M/MArtikel.php';
require_once 'M/MLeser.php';
require_once 'V/VConfirm.php';

$mart = new MArtikel();
$mleser = new MLeser();
$v = new VConfirm();
$c = new CConfirm();
$c->work($_GET, $_POST, $mart, $mleser, $v);
