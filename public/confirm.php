<?
require_once 'path2private.php';
require_once PATH_PRIVATE.'C/CConfirm.php';

$c = new CConfirm();
$c->work($_GET, $_POST, 0);
