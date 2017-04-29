<?
session_start();

require_once 'path2private.php';
require_once PATH_PRIVATE.'C/CArtikel.php';

$c = new CArtikel();
$c->work($_GET, $_POST, '');
