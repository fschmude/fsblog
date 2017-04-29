<?
/**
 * Eine sitemap für webmaster.google.com erzeugen
 */
require_once 'path2private.php';
require_once PATH_PRIVATE.'C/CSitemap.php';

$c = new CSitemap;

$c->work(null, null, null);
