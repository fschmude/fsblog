<?
// zeige einen Vorschlagstext für das Teaser-Mail an alle Abonnenten
session_start();

require_once 'config.php';
require_once 'C/CMailtext.php';

$c = new CMailtext();
$c->run($_GET, $_POST, $_FILES);

