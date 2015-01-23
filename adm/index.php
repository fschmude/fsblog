<?
session_start();
include_once 'CStart.php';
$c = new CStart($_GET, $_POST);
$c->display();
