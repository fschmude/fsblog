<?
require_once 'path2private.php';
require_once PATH_PRIVATE.'C/CListe.php';

$c = new CListe();
$c->work($_GET, $_POST, '');
