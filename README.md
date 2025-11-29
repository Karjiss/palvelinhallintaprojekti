# palvelinhallintaprojekti

Raportin ja projektin luojat: Alex Lindh & Jani Karjalainen

Miniprojekti palvelinten hallinta-kurssille.

## Alkutilanne

Projektin ideana oli luoda SaltStackkia käyttäen automaattinen tila, missä asennetaan verkkopalvelin, tehdään sinne muutoksia ja tarkistellaan verkkokäyttäytymistä.

Projektin alkupisteessä asensimme saltstackin ja loimme uuden repositorion projektillemme (ohjeiden mukaisesti!)

## Verkkopalvelimen asennus

Aloitimme verkkopalvelimen asentamisen käsin ensimmäiseksi.

    $ sudo apt-get update

    $ sudo apt-get install apache2

Loin testisivun "/var/www/html/index.html", jonka muokattua testasin verkkosivujen toiminnan!

<img width="1194" height="469" alt="kuva" src="https://github.com/user-attachments/assets/5a16bbf3-9e63-48ab-a917-bf986eaea158" />
Toimii!

## Muiden pakettien asentaminen

Seuraavaksi aloitin pakettien asentamisen käyttäen saltstackkia säästääkseni aikaa.

Loin uuden hakemiston saltille komennolla:

        '$ sudo mkdir /srv/salt/packages'

        '$ sudoedit init.sls'

Ja loin seuraavanlaisen koodinsisällön:

<img width="444" height="144" alt="kuva" src="https://github.com/user-attachments/assets/486a6931-5b84-4279-aa49-74be44c7a4f8" />

Asennettavan paketit!

<img width="529" height="273" alt="kuva" src="https://github.com/user-attachments/assets/4c475ff9-2338-4fc2-afc6-f935e03391fe" />

Kaikki toimii!


## porttiskannaus

Seuraavana aloitimme porttiskannauksen ja siitä raportin saamisen.

Testasimme ensin portskannin toiminnan komennolla:

        '$ nmap 127.0.0.1'

<img width="581" height="213" alt="kuva" src="https://github.com/user-attachments/assets/b3658c7b-1758-44f5-9905-d8f95236ce17" />

Skannaus toimii!

Seuraavaksi sen tallentaminen raporttiin

        '$ nmap 127.0.0.1 > /tmp/raportti'

Äskeisellä komennolla saimme raportin tallennettua /tmp/ kansioon.

<img width="578" height="226" alt="kuva" src="https://github.com/user-attachments/assets/59809287-9c93-4f5d-9e04-d07ae7b4b933" />

Tallennettu raportti!



## Automatisointi käyttäen saltstackkia

# Lähteet

https://github.com/AlexLindh/Configuration-management/blob/main/h1-viisikko.md

https://github.com/AlexLindh/Configuration-management/blob/main/h5-toimiva-versio.md
