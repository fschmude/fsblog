<?
session_start();

require_once 'config.php';
require_once 'C/CAdmin.php';

$c = new CAdmin();
$c->run($_GET, $_POST, $_FILES);

