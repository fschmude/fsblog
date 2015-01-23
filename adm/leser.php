<?
session_start();
include_once 'CLeser.php';
$c = new CLeser($_GET, $_POST);
$c->display();
