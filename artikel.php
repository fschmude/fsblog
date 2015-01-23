<?
require_once 'C/CArtikel.php';
require_once 'M/MArtikel.php';
require_once 'V/VArtikel.php';

$mart = new MArtikel();
$v = new VArtikel();

$c = new CArtikel();
$c->work($_GET, $_POST, $mart, $v);

