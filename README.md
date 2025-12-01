# HAP - Haavoittuva Automatisoitu Palvelin

Projektin ideana oli luoda SaltStackillä automaattinen tila, mikä asentaa verkkopalvelimen ja tekee siihen muutoksia. Haluttiin myös luoda erillinen verkkosivu, joka on tarkoituksellisesti erittäin haavoittuva.

Loimme verkkopalvelimelle haavoittuvan sivun tekoälyn avulla. Haavoittuvuuksia pystyy hyödyntämään esimerkiksi Kali Linuxilla sen sisäänrakennetuilla työkaluilla.

## Haavoittuvan palvelimen asennusohjeet

### [Saltin asennusohjeet](https://github.com/Karjiss/server-management-course/blob/main/h1-viisikko.md#b-saltin-asennus-linuxille-salt-minion) (Karjalainen 2025) tai [**Saltin asennus Linuxille**](https://github.com/AlexLindh/Configuration-management/blob/main/h1-viisikko.md) (Lindh 2025)

### Virtuaalikoneet
- Virtualisointiohjelmisto: **VMWare Workstation Pro**
- Virtuaalikoneet: **Debian 13 Trixie**(slave) ja **Kali Linux** (master)

Suosittelemme käyttämään Kali Linuxia, jos haluat kokeilla hyökkäystyökaluja, sillä niiden asennus esim. Debianille on paljon monimutkaisempaa (Kalissa valmiina paljon).

Saltin asennuksen jälkeen asetetaan Kali Debianin masteriksi muokkaamalla ```/etc/salt/minion```-tiedostoa Debian-koneella komennolla: ```$ sudo nano /etc/salt/minion```.  

<img width="619" height="413" alt="image" src="https://github.com/user-attachments/assets/1c4c5981-56ea-4f39-80cc-39ff96fd61d3" />

- Lisää rivi = master: (masterin_ip_osoite)
- Vapaaehtoisesti lisättävä rivi = id: (Slavekoneen-nimi)
  
Tässä vaiheessa käynnistetään salt-minion uudelleen komennoilla: 
    
    $ sudo systemctl stop salt-minion

ja

    $ sudo systemctl start salt-minion

Sitten siirrytään master-koneelle ja ajetaan komento: ```$ sudo salt-key -A```, jotta voidaan hyväksyä "slave".

<img width="306" height="104" alt="image" src="https://github.com/user-attachments/assets/0a74b391-0c4e-4593-bb6d-bc2e57d41b49" />

### Salt tilat

Aloitetaan kloonaamalla tämä Git-varasto komennolla:

    $ git clone https://github.com/Karjiss/palvelinhallintaprojekti.git

Seuraavaksi kopioi Salt-hakemisto Git-varastosta omaan paikalliseen ```/srv/salt``` hakemistoon:

    $ sudo cp -r ~/palvelinhallintaprojekti/srv/salt/* /srv/salt/

Aja tilat masterilta komennolla: 

    $ sudo salt '*' state.apply

<img width="510" height="564" alt="image" src="https://github.com/user-attachments/assets/984a496a-c5e0-400d-8132-2b11d23006a0" />


  **Tulos:**

  <img width="955" height="489" alt="image" src="https://github.com/user-attachments/assets/80750629-11e6-4c5d-873c-d18d7f1bd1c1" />

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
