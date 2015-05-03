<?
require_once 'C/CListe.php';
require_once 'M/MArtikel.php';
require_once 'V/VListe.php';

$mart = new MArtikel();
$vliste = new VListe();
$c = new CListe();
$c->work($_GET, $_POST, $mart, $vliste, 'alle');
