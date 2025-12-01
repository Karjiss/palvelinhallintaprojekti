# h6 - Miniprojekti

Raportin ja projektin luojat: Alex Lindh & Jani Karjalainen

Miniprojekti palvelinten hallinnan kurssille.

### Käyttöympäristö

- Virtualisointiohjelmistot: **VMWare Workstation Pro** ja **Oracle VirtualBox**
- Virtuaalikoneet: **Debian 13 Trixie** ja **Kali Linux**


## Alkutilanne

Projektin ideana oli luoda SaltStackillä automaattinen tila, mikä asentaa verkkopalvelimen ja tekee siihen muutoksia. Halusimme myös luoda erillisen verkkopalvelimen, joka on tarkoituksellisesti haavoittuva.

Loimme verkkopalvelimelle haavoittuvan sivun tekoälyn avulla. Haavoittuvuuksia pystyy hyödyntämään esimerkiksi Kali Linuxilla sen sisäänrakennetuilla työkaluilla. 


Projektin alkupisteessä asensimme SaltStackin ja loimme uuden repositorion projektillemme näiden ohjeidein mukaisesti: [**h1-Viisikko**](https://github.com/AlexLindh/Configuration-management/blob/main/h1-viisikko.md) (Lindh 2025).

## Verkkopalvelimen asennus

Aloitimme verkkopalvelimen asentamisen ensin käsin.

    $ sudo apt-get update

    $ sudo apt-get install apache2

Loimme testisivun "/var/www/html/index.html", jonka muokattua testasin verkkosivujen toiminnan:

<img width="1194" height="469" alt="kuva" src="https://github.com/user-attachments/assets/5a16bbf3-9e63-48ab-a917-bf986eaea158" />

- Toimii!


## Muiden pakettien asentaminen

Tästä löytyy vielä toiset [Saltin asennusohjeet](https://github.com/Karjiss/server-management-course/blob/main/h1-viisikko.md#b-saltin-asennus-linuxille-salt-minion)(Karjalainen 26.10.2025), mikäli et jaksa scrollata ylös asti.

Seuraavaksi aloitimme pakettien asentamisen käyttäen saltstackkia säästääksemme aikaa.

Loin uuden hakemiston saltille komennolla:

    $ sudo mkdir /srv/salt/packages

Sitten loimme init.sls-tiedoston:

    $ sudoedit /srv/salt/packages/init.sls
    
Kirjoitimme tekstieditorilla seuraavan koodinpätkän tiedostoon:

[**init.sls**](https://github.com/Karjiss/palvelinhallintaprojekti/blob/main/srv/salt/packages/init.sls)
```
packages:
  pkg.installed:
    - pkgs:
      - wget
      - nmap
      - tree
      - cowsay
```      



Asennettavat paketit:

<img width="529" height="273" alt="kuva" src="https://github.com/user-attachments/assets/4c475ff9-2338-4fc2-afc6-f935e03391fe" />

- Kaikki toimii!


## Porttiskannaus

Seuraavana aloitimme porttiskannauksen, sekä siitä lokin luomisen.

Testasimme ensin portskannin toiminnan komennolla:

    $ nmap 127.0.0.1

<img width="581" height="213" alt="kuva" src="https://github.com/user-attachments/assets/b3658c7b-1758-44f5-9905-d8f95236ce17" />

- Skannaus toimii!

Seuraavaksi sen tallentaminen raporttiin:

    $ nmap 127.0.0.1 > /tmp/raportti

Äskeisellä komennolla saimme raportin tallennettua /tmp/ kansioon.

<img width="578" height="226" alt="kuva" src="https://github.com/user-attachments/assets/59809287-9c93-4f5d-9e04-d07ae7b4b933" />

- Tallennettu raportti!


## Automatisointi käyttäen SaltStackia

Tässä automatisoimme kaiken edellä käydyn SaltStackin avulla.

Aloitimme luomalla top.sls tiedoston, koska tilojen kirjoittaminen yksitellen on tylsää ja ajanvievää: 

    
    $ sudoedit srv/salt/top.sls

- [Ohje top-filen luontiin](https://github.com/Karjiss/server-management-course/blob/main/h2-infraa-koodina.md#b-topping) (Karjalainen 02.11.2025)




Seuraavaksi loimme hakemiston apachelle, jotta sen voisi automatisoida:

    $ sudoedit /srv/salt/apache2/init.sls

- Kopioin aiemmin luodun ```/var/www/html/index.html```-tiedoston myös tähän hakemistoon: ```/srv/salt/apache2```

Lisäsimme init.sls sisällön:

[**init.sls**](https://github.com/Karjiss/palvelinhallintaprojekti/blob/main/srv/salt/apache2/init.sls) (Hyperlinkin sisällä päivitetty versio)

```
apache2:
  pkg.installed
/var/www/html/index.html:
  file.managed:
    - source: "salt://apache2/index.html"
apache2.service:
  service.running:
    - watch:
      - file: /var/www/html/index.html
```
<img width="711" height="435" alt="kuva" src="https://github.com/user-attachments/assets/c83db782-13de-4ea4-9620-37c593f08d14" />

- Kaikki toimii!

Seuraavaksi lisäsin porttiskannauksen Salttiin, eli luotiin taas oma hakemisto, johon init.sls:

    $ sudoedit /srv/salt/portscan/init.sls

[init.sls](https://github.com/Karjiss/palvelinhallintaprojekti/blob/main/srv/salt/portscan/init.sls) 

```
portscan:
  cmd.run:
    - name: 'nmap 127.0.0.1 > /tmp/raportti.txt'
```

<img width="722" height="416" alt="kuva" src="https://github.com/user-attachments/assets/6c0907a9-2f08-4df8-b5ba-5dd6ccc94be0" />

- Skannaus toimii, sekä toimittaa "raportin" haluttuun kansioon.
- Tämä ei ole idempotentti ratkaisu, sillä se kirjoittaa aina vanhan päälle, muutoksista riippumatta.

Seuraavaksi loimme tilan, joka asentaa palomuurin, käynnistää sen ja avaa HTTP portin:

    $ sudoedit /srv/salt/firewall/init.sls


[**init.sls**](https://github.com/Karjiss/palvelinhallintaprojekti/blob/main/srv/salt/firewall/init.sls)

```

ufw-package:
  pkg.installed:
    - name: ufw
ufw-allow-http:
  cmd.run:
    - name: 'ufw allow 80/tcp'
    - unless: 'ufw status | grep -E "80/tcp|WWW"'
    - require:
      - service: ufw-enable
ufw-enable:
  service.running:
    - name: ufw
    - enable: True
    - require:
      - pkg: ufw-package
show-ufw-status:
  cmd.run:
    - name: 'sudo ufw status'
```

Lisäsimme kaikki hakemistot/ajettavat tilat top.sls tiedostoomme, jotta kaikki tilat voidaan ajaa kerralla:

[**top.sls**](https://github.com/Karjiss/palvelinhallintaprojekti/blob/main/srv/salt/top.sls)

```
base:
  '*':
    - apache2
    - packages
    - firewall
    - portscan
```

Ajoimme top-filen:

<img width="505" height="498" alt="image" src="https://github.com/user-attachments/assets/357408d6-4ed9-4254-9c41-5ed5579cd192" />

- Ei erroreita, 2 muutosta.
- Tila: ```cmd.run``` ei periaatteessa tee muutoksia, prosessien ID muuttuu vain joka ajokerralla.



## Haavoittuva Apache2-palvelin --- Projektin etusivun Readme/asennusohje on lyhennetty versio tästä + tehty enemmän automaattisesti

### Käyttöympäristö 

- Käyttöjärjestelmä: Microsoft Windows 10 Home
- Emolevy: Gigabyte Z170-Gaming K3
- Prosessori: Intel i5-6600K
- Näytönohjain: NVIDIA GeForce RTX 2060
- RAM: 16 GB DDR4
- Virtualisointiohjelmisto: **VMWare Workstation Pro**
- Virtuaalikoneet: **Debian 13 Trixie** ja **Kali Linux**
Suosittelemme käyttämään Kali Linuxia, jos haluat kokeilla hyökkäystyökaluja, sillä niiden asennus esim. Debianille on paljon monimutkaisempaa.


Tässä osiossa oli tarkoitus ajaa Saltilla aikaisemmat tilat masterilta (Kali) slavelle (Debian).

Tätä varten loimme myös tekoälyn (Gemini) avulla haavoittuvan verkkosivun, johon voisi hyökätä Kalilla tai selaimella. Lisäsimme sen Apache2-tilaan seuraavanlaisesti:

Komennolla: ```$ sudo nano /srv/salt/apache2/vulnerable.php``` lisätään tekstieditorilla tiedosto, jonka sisälle syötetään [vulnerable.php](https://github.com/Karjiss/palvelinhallintaprojekti/blob/main/srv/salt/apache2/vulnerable.php)-tiedoston HTML-koodi.

Lisätään vanhaan init.sls-tiedostoon tiedot uudesta weppisivusta, jotta automatisoidaan tämäkin osuus:

Muokataan init.sls komennolla:

    $ sudoedit /srv/salt/apache2/init.sls

[**init.sls**](https://github.com/Karjiss/palvelinhallintaprojekti/blob/main/srv/salt/apache2/init.sls)


```
apache2:
  pkg.installed
/var/www/html/index.html:
  file.managed:
    - source: "salt://apache2/index.html"
/var/www/html/vulnerable.php:
  file.managed:
    - source: "salt://apache2/vulnerable.php"
apache2.service:
  service.running:
    - watch:
      - file: /var/www/html/index.html
      - file: /var/www/html/vulnerable.php
```

Tuloksena pitäisi olla haavoittuva apache2 weppisivu, jota voi hyödyntää vaikka Kalin työkalujen kokeilemiseen turvallisesti.



Saltin asennuksen jälkeen asetimme Kalin Debianin masteriksi muokkaamalla ```/etc/salt/minion```-tiedostoa komennolla: ```$ sudo nano /etc/salt/minion```.  

<img width="619" height="413" alt="image" src="https://github.com/user-attachments/assets/1c4c5981-56ea-4f39-80cc-39ff96fd61d3" />

- Lisätty rivi = master: (Master-IP)
- Vapaaehtoisesti lisättävä rivi = id: (Slavekone-ID)
  
Tässä vaiheessa käynnistettiin salt-minion uudelleen komennoilla: ```$ sudo systemctl stop salt-minion``` ja ```$ sudo systemctl start salt-minion```.

Sitten siirryimme master-koneelle ja ajoimme komennon: ```$ sudo salt-key -A```, jotta voimme hyväksyä "slaven".

<img width="306" height="104" alt="image" src="https://github.com/user-attachments/assets/0a74b391-0c4e-4593-bb6d-bc2e57d41b49" />

Ajettiin tilat masterilta komennolla: 

    $ sudo salt '*' state.apply

<img width="543" height="607" alt="image" src="https://github.com/user-attachments/assets/9005ac65-01d7-4ed6-84d9-20eca842508f" />

- Error viittasi nmap-ohjelman puuttumiseen. Korjaus tehty /packages/init.sls tiedostoon.

<img width="544" height="609" alt="image" src="https://github.com/user-attachments/assets/472a067f-bbd2-40a7-824a-870ab2c2673b" />

- Korjauksen jälkeen uudelleenajo suoriutui ilman virheitä, joten vika ei ollut iso. Korjattu myös raportin alkuosasta, sekä git-varaston tiedostosta.

  **Tulos:**

  <img width="955" height="489" alt="image" src="https://github.com/user-attachments/assets/80750629-11e6-4c5d-873c-d18d7f1bd1c1" />

  - Nmap ajon raportti tallentui oikeaan paikkaan.
  - Cowsay asentui oikein.
  - Haavoittuva testisivu on pystyssä paikallisesti.
  - Virtuaalikone on nyt valmis Kali Linuxin rääkättäväksi.

# Lähteet

Lindh, A. 21.10.2025. h1-viisikko. Luettavissa: https://github.com/AlexLindh/Configuration-management/blob/main/h1-viisikko.md. Luettu 29.11.2025

Lindh, A. 20.11.2025. h5 toimiva versio. Luettavissa: https://github.com/AlexLindh/Configuration-management/blob/main/h5-toimiva-versio.md. Luettu 29.11.2025

Lindh, A. 15.10.2025. h4 - pkg-file-service. Luettavissa: https://github.com/AlexLindh/Configuration-management/blob/main/h4-pkg-file-service.md. Luettu 30.11.2025

Google Gemini. 29.11.2025. Käytetty luomaan pohja/testi verkkosivu, johon pystyy kohdistamaan Kali Linuxilla hyökkäyksiä. Generoi myös kokeiltavia komentoja, millä hyökätä. Käytettävissä: https://gemini.google.com/

Promptit: 

<img width="432" height="73" alt="image" src="https://github.com/user-attachments/assets/87f515b9-44fc-47e5-b4ed-76e7167f29b1" />    <img width="481" height="74" alt="image" src="https://github.com/user-attachments/assets/2f356f5a-36c2-4c1a-a1bb-9c08dce52936" />    


Karjalainen, J. 26.10.2025. h1-viisikko. Luettavissa: https://github.com/Karjiss/server-management-course/blob/main/h1-viisikko.md. Luettu 30.11.2025.

Karjalainen, J. 2.11.2025. h2-infraa-koodina. Luettavissa: https://github.com/Karjiss/server-management-course/blob/main/h2-infraa-koodina.md#b-topping. Luettu 30.11.2025.
