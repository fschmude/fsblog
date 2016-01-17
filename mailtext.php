<?
// zeige einen Vorschlagstext für das Teaser-Mail an alle Abonnenten
session_start();

require_once 'C/CMailtext.php';

$c = new CMailtext();
$c->work($_GET, $_POST, $_FILES);

