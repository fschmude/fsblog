<?
require_once 'M/MArtikel.php';
require_once 'C/CRss.php';
require_once 'V/VRss.php';

$m = new MArtikel();
$v = new VRss();
$c = new CRss();
$c->work($m, $v);

