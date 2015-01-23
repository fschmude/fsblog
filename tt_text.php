<?
include 'M/Model.php';
echo __LINE__.' ok<br>';

$m = new Model();
$stmt = $m->get_pdo()->prepare('select * from bilder');
if (!$stmt->execute()) {
  echo 'Fehler!!!';
}
$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
var_dump($res);
echo __LINE__.' ok<br>';

echo 'jetzt der update<br>';
$sql = 'update artikel set text=\'Die Nichtdebatte rund um den Islam hat es anlässlich der Ermordung einer kompletten Zeitungsredaktion geschafft, noch verlogener und jedem freiheitsliebenden Menschen widerwärtiger zu werden, als sie es in den letzten Jahren ohnehin schon war.
Zwei Hauptmuster der Nichtdebatte waren zu erwarten und sind nun, zwei Tage nach dem neuesten actum fidei der Islamisten in voller Blüte zu betrachten.
Das eine ist, den Opfern die Schuld an ihrer Ermordung zuzuschieben, das andere ist, Islamkritiker als irgendwie "gleich böse wie die Islamisten" hinzustellen.
Gekrönt wird die mainstreamige Jämmerlichkeit dann mit billiger, folgenloser und verlogener Scheinsolidarität.

<h2>Die Opfer sind selbst schuld</h2>
Seit die Süddeutsche Zeitung das Argument (hust!) für originell hielt, verwende ich es auch, nämlich als abschreckendes Beispiel: Das Schinkenbrot-Argument.

Anlässlich eines Anschlags von allzu gläubigen Muslimen im Jahr 2010 schrieb die dicke Berta des linken Mainstream damals:
"Man kann ein Werk der Weltliteratur [die Satanischen Verse von Salman Rushdie] nicht mit der plumpen Witzelei eines dänischen Karikaturisten vergleichen. Das eine ist eine intellektuelle Meisterleistung, die es zu verteidigen gilt; das andere eine bewusste Provokation, die ungefähr so intelligent ist, wie der Versuch, einen Tiger zu erziehen, indem man ihm erst ein Schinkenbrot anbietet und es ihm dann wieder wegnimmt."

Im Internet ist diese Gemme nur noch <a href="http://www.achgut.com/dadgdx/index.php/dadgd/article/der_rabauke_und_der_feingeist/" target="_blank">indirekt bei der Achse der Guten</a> zu finden. 

Heute fressen die linken Journalisten wieder tonnenweise Schinkenbrot:
Hätten sie halt der - für nichts verantwortlichen - islamischen Gemeinde keinen haram Schinken serviert, diese miesen Provokateure von Charlie Hebdo.

<h2>Islamkritiker genauso schlimm wie Islamisten</h2>
Gleichsetzung von Islamisten mit Islamkritikern in einem Qualitätsmedium:
<a href="http://www.faz.net/aktuell/politik/kommentar-zum-anschlag-auf-satiremagazin-charlie-hebdo-13358326.html" target="_blank">http://www.faz.net/aktuell/politik/kommentar-zum-anschlag-auf-satiremagazin-charlie-hebdo-13358326.html</a>

Weitere Beispiele herauszusuchen spare ich mir jetzt.
Das Gleichsetzungsmuster ist so allgegenwärtig, dass diese allumfassende Bankrotterklärung der FAZ nicht mal bemerkenswert ist.
Sie ist nur ein Beispiel von vielen.

<h2>Billige Scheinsolidarität</h2> 
Das größte Kotzen kommt mir aber, wenn genau diejenigen Journalisten und Politiker, die seit vielen Jahren die Islamisierung (Einhaltung islamischer Regeln auch für Nichtmuslime) vorantreiben, nun mit "Je suis Charlie" billigste Populismus-Punkte machen wollen.
Nein, Ihr seid definitiv nicht Charlie.
Ihr hattet keine Sekunde lang den Mut, den die Leute von Charlie Hebdo täglich bewiesen haben.
Ihr seid einfach nur feige und wollt nach wie vor um keinen Preis bei den Islamikern anecken.
Schlimmer noch, ihr verquickt weiterhin Eure eigenen Wahnvorstellungen (Pegida schlimm wie Hitler) mit den Wünschen der islamischen Missionare (Islam ist Frieden) und betätigt Euch so als regelrechte Vorhut der Islamisierung.

Aus diesen Gründen poste ich auch kein "je suis Charlie", obwohl ich mehr Druckwerke von Charlie besitze als schätzungsweise 99,5% aller Deutschen.

Wie mutig es im Westen wirklich aussieht, wollte ich eigentlich einen linken Karikaturisten sagen lassen, nämlich Ralf König.
Dieser hat gestern auf Facebook einen sehr bemerkenswerten Text darüber geschrieben, wieviele Ausstellungen/Filme/Bücher/Events/... in den letzten Jahren wohl nicht stattgefunden haben, und zwar allein aus Angst vor möglichen Drohungen, Störungen etc. aus muslimischen Kreisen.
Als aber ausgerechnet böse AfD-ler anfingen, diesen Post zu "teilen" (weiterzuverbreiten), löschte Ralf König ihn kurzerhand.
So demonstriert man seine Solidarität mit dem linken Mainstream am effektivsten, nämlich durch das Löschen der eigenen Meinung.
Das finde ich sehr schade, weil ich durchaus ein Fan des Künstlers Ralf König bin.

Es gilt weiterhin:
Solange Leute wie Nicolaus Fest gefeuert werden, sobald sie etwas islamkritisches schreiben, haben die Terroristen gewonnen.
Ach ja, der <a href="http://nicolaus-fest.de/101/" target="_blank">aktuelle Text von Nicolaus Fest</a> ist lesenswert.

Und solange Hebdo-Karikaturen entweder gar nicht oder nur auf hinteren Seiten nachgedruckt werden, auf den Titelseiten aber kopfwackelnd vor "Islamophobie" im Allgemeinen und Pegida im Speziellen gewarnt wird, solange haben die Terroristen gewonnen.

<imga id="14">
Die Morgenpost als lobenswerte Ausnahme
\''
  .' where id=28'
;
$m->get_pdo()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
$stmt = $m->get_pdo()->prepare($sql);
if (!$stmt->execute()) {
  echo 'Fehler!!!';
}
echo __LINE__.' ok<br>';

