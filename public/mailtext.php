<?
// zeige einen Vorschlagstext für das Teaser-Mail an alle Abonnenten
session_start();

require_once 'path2private.php';
require_once PATH_PRIVATE.'C/CMailtext.php';

$c = new CMailtext();
$c->work($_GET, $_POST, $_FILES);
