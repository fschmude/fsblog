<?
session_start();

require_once 'path2private.php';
require_once PATH_PRIVATE.'C/CAdmin.php';

$c = new CAdmin();
$c->work($_GET, $_POST, $_FILES);

