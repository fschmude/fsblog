<?
require_once 'M/MArtikel.php';
require_once 'V/VStatic.php';
require_once 'C/CStatic.php';

$mart = new MArtikel();
$v = new VStatic();
$c = new CStatic();
$c->work($mart, $v, 'about');
