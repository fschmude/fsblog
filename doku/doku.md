% Dokumentation von fsblog
% Fritz Schmude
% 2016-07-01

# Über diese Doku
Wenn etwas an dieser Doku geändert werden soll, dann 

#. Hier ändern
#. In Verzeichnis doku wechseln
#. pandoc -t html -o doku.htm -s --toc -N --template doktpl.htm doku.md
#. Nun sind die Änderungen auch in doku.htm
#. Sowohl doku.md, als auch doku.htm einchecken

Zu pandoc siehe <http://www.pandoc.org/>

# Aufbau von allem

M[Objekt] enthält optional:

- getList
- getItem
- deleteItem
- editItem

D[Tabelle] enthält (von DB geerbt)

- getAll
- getRow
- delete
- edit
- create
- createValues

V enthält

- display(errmsg, data);

# Deployment
Entwicklung, Tests fertig?  
dann: git push github außer config.php

Schön wäre folgendes zu automatisieren, wenn man nach Produktion deployen könnte:  
Nach git clone oder pull in Produktion:

#. Passwörter anpassen
    config.php

#. Beschreibbare Verzeichnisse erstellen:  
    /log  
    /imga  

#. Last-Login-Datei erstellen  
  cd log  
  echo '2001-03-29 11:00' > last_login.txt  
  chmod 666 last_login.txt
  
#. Löschen (bzw. gar nicht erst deployen):  
  /tests  
  /doku  
  README.md
  
# Testanleitung
folgende URLs müssen erhalten bleiben:

- index.php (aber nur wg. domain-Ansteuerung)
- imga/AfD-Mitgliederentscheid.pdf
- imga/Brigitte_Meier.mp4
- artikel.php?aid=23
- artikel/zettel.php, zettel.php
- rss.php
- Klick auf Monat, Schnippel, neuen Artikel mit Bild, alten Artikel mit Bild, alle, kontakt
- google...htm


