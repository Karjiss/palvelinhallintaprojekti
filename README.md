# palvelinhallintaprojekti

Raportin ja projektin luojat: Alex Lindh & Jani Karjalainen

Miniprojekti palvelinten hallinnan kurssille.


## Alkutilanne

Projektin ideana oli luoda SaltStackkia käyttäen automaattinen tila, missä asennetaan verkkopalvelin, tehdään sinne muutoksia ja tarkistellaan verkkokäyttäytymistä.

Loimme verkkopalvelimelle haavoittuvan sivun tekoälyn avulla. Haavoittuvuuksia pystyi hyödyntämään Kali Linuxilla sen sisäänrakennetuilla työkaluilla.


Projektin alkupisteessä asensimme SaltStackin ja loimme uuden repositorion projektillemme (ohjeiden mukaisesti!).

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

```
packages:
  pkg.installed:
    - pkgs:
      - wget
      - tree
      - cowsay 
```      

Tää on ehdotus muotoilu ^^^^

<img width="444" height="144" alt="kuva" src="https://github.com/user-attachments/assets/486a6931-5b84-4279-aa49-74be44c7a4f8" />

Asennettavan paketit!

<img width="529" height="273" alt="kuva" src="https://github.com/user-attachments/assets/4c475ff9-2338-4fc2-afc6-f935e03391fe" />

Kaikki toimii!


## Porttiskannaus

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


## Automatisointi käyttäen SaltStackia

Seuraavaksi aloitimme kaiken tämän automatisoinnin saltstackin avulla!

Aloin luomalla top.sls tiedoston, koska tilojen kirjoittaminen yksitellen on tylsää :D

Seuraavaksi loin hakemiston apachelle, jotta sen voisi automatisoida.

    '$ sudoedit /srv/salt/apache2/init.sls'

Ja kopioin aiemmin luodun /var/www/html/index.html tiedoston myös tähän hakemistoon /srv/salt/apache2



<img width="412" height="189" alt="kuva" src="https://github.com/user-attachments/assets/ae25fdcb-8f3f-47ce-906d-550ee2d3e93e" />

/srv/salt/apache2/init.sls -tiedoston koodi

<img width="711" height="435" alt="kuva" src="https://github.com/user-attachments/assets/c83db782-13de-4ea4-9620-37c593f08d14" />

Kaikki toimii!

Seuraavaksi lisäsin porttiskannauksen salttiin

    '$ sudoedit /srv/salt/portscan/init.sls'

<img width="460" height="82" alt="kuva" src="https://github.com/user-attachments/assets/a2716d53-d40d-460d-abd9-65f1f1bbdbd3" />

Porttiskannauksen koodi!

<img width="722" height="416" alt="kuva" src="https://github.com/user-attachments/assets/6c0907a9-2f08-4df8-b5ba-5dd6ccc94be0" />

Kaikki toimii!

Seuraavaksi asensin tulimuurin saltin avulla, joka käynnistää ja avaa http portin.

    '$ sudoedit /srv/salt/firewall/init.sls'

<img width="505" height="295" alt="kuva" src="https://github.com/user-attachments/assets/586a83fe-3b5f-4ae1-b6e4-bbaac0c48184" />

init.sls tiedoston koodi.

<img width="766" height="810" alt="kuva" src="https://github.com/user-attachments/assets/6fff8214-5126-41a9-96d0-bfbf1455ced9" />

Kaikki toimii!

Samalla olin lisännyt kaikki hakemistot/ajettavat tilat top.sls tiedostoonl, jotta kaikki tilat ajetaan samalla.

<img width="835" height="162" alt="kuva" src="https://github.com/user-attachments/assets/e9958991-545d-4da6-a909-9b3573c5095a" />

Top.sls tiedston sisältö

## Haavoittuva Apache2-palvelin

### Käyttöympäristö

- Käyttöjärjestelmä: Microsoft Windows 10 Home
- Emolevy: Gigabyte Z170-Gaming K3
- Prosessori: Intel i5-6600K
- Näytönohjain: NVIDIA GeForce RTX 2060
- RAM: 16 GB DDR4
- Virtualisointiohjelmisto: **VMWare Workstation Pro**
- Virtuaalikoneet: **Debian 13 Trixie** ja **Kali Linux**

Tässä osiossa oli tarkoitus ajaa Saltilla aikaisemmat tilat masterilta (Kali) slavelle (Debian).

Tätä varten loimme myös tekoälyn (Gemini) avulla haavoittuvan verkkosivun, johon voisi hyökätä Kalilla tai selaimella. Lisäsimme sen Apache2 tilaan seuraavanlaisesti:

Komennolla: ```$ sudo nano /srv/salt/apache2/vulnerable.php``` lisätään tekstieditorilla tiedosto, jonka sisälle syötetään [vulnerable.php](https://github.com/Karjiss/palvelinhallintaprojekti/blob/main/srv/salt/apache2/vulnerable.php)-tiedoston HTML-koodi.

Tuloksena pitäisi olla haavoittuva apache2 weppisivu, jota voi hyödyntää vaikka Kalin työkalujen kokeilemiseen turvallisesti.

// Ohjeet Saltin asennukseen löytyvät raporteistamme. TEE ALKUUN SE TUTORIAALI JNEJNE LÄHDEVIITTAUS JNEJNE...


Saltin asennuksen jälkeen asetimme Kalin Debianin masteriksi muokkaamalla ```/etc/salt/minion```-tiedostoa komennolla: ```$ sudo nano /etc/salt/minion```.  

<img width="609" height="406" alt="image" src="https://github.com/user-attachments/assets/7bcfd994-b589-42eb-b76b-ed21c5078068" />

- Lisätty rivi = master: (Master-IP)
- Vapaaehtoisesti lisättävä rivi = id: (Slavekone-ID)
  
Tässä vaiheessa käynnistettiin salt-minion uudelleen komennolla: ```$ sudo systemctl stop salt-minion``` ja ```$ sudo systemctl start salt-minion```.

Sitten siirryimme master-koneelle ja ajoimme komennon: ```$ sudo salt-key -A```, jotta voimme hyväksyä "slaven".

<img width="306" height="104" alt="image" src="https://github.com/user-attachments/assets/0a74b391-0c4e-4593-bb6d-bc2e57d41b49" />






# Lähteet

Lindh, A. 21.10.2025. h1-viisikko. Luettavissa: https://github.com/AlexLindh/Configuration-management/blob/main/h1-viisikko.md. Luettu 29.11.2025

Lindh, A. 20.11.2025. h5 toimiva versio. Luettavissa: https://github.com/AlexLindh/Configuration-management/blob/main/h5-toimiva-versio.md. Luettu 29.11.2025

Lindh, A. 15.10.2025. h4 - pkg-file-service. Luettavissa: https://github.com/AlexLindh/Configuration-management/blob/main/h4-pkg-file-service.md. Luettu 30.11.2025
