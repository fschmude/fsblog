<?
require_once 'path2private.php';
require_once PATH_PRIVATE.'C/CStatic.php';

$c = new CStatic();
$c->work(array('page' => 'about'), 0, 0);
