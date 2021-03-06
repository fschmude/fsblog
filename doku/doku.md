% Dokumentation von fsblog
% Fritz Schmude
% 2016-07-01

# Über diese Doku
Wenn etwas an dieser Doku geändert werden soll, dann 

#. In Verzeichnis doku wechseln
#. In doku.md Änderungen durchführen
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

#. Datenbank erstellen  
  Aufruf von [datadic.php](datadic.php) liefert das Erstellungsskript für die Datenbank.
  
#. config.php anpassen
  
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

## Klicken
- Klick auf Monat, Schnippel, neuen Artikel mit Bild, alten Artikel mit Bild, alle, about, Kontakt, RSS
- Google-Suche

## URLs testen
- index.php (aber nur wg. domain-Ansteuerung)
- imga/AfD-Mitgliederentscheid.pdf
- imga/austria.mp4
- artikel.php?aid=23
- artikel/zettel.php, zettel.php

## Mit DB-Verfolgung und Mail
- Neues Abo
- Kommentieren (SELECT id, username, usermail, text, status FROM posts ORDER BY id DESC)
- google...htm


