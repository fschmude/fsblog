<?
session_start();

echo __LINE__.'<br>';
flush();
require_once 'config.php';
echo __LINE__.'<br>';
flush();
require_once 'C/CAdmin.php';
echo __LINE__.'<br>';
flush();

$c = new CAdmin();
echo __LINE__.'<br>';
flush();
$c->run($_GET, $_POST);
