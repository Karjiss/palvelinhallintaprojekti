# HAP - Haavoittuva Automatisoitu Palvelin
Projektin ideana oli luoda SaltStackillä automaattinen tila, mikä asentaa verkkopalvelimen ja tekee siihen muutoksia. Halusimme myös luoda erillisen verkkopalvelimen, joka on tarkoituksellisesti haavoittuva.

Loimme verkkopalvelimelle haavoittuvan sivun tekoälyn avulla. Haavoittuvuuksia pystyy hyödyntämään esimerkiksi Kali Linuxilla sen sisäänrakennetuilla työkaluilla.

## Haavoittuvan palvelimen asennusohjeet

### [Saltin asennusohjeet](https://github.com/Karjiss/server-management-course/blob/main/h1-viisikko.md#b-saltin-asennus-linuxille-salt-minion)

### Virtuaalikoneet
- Virtualisointiohjelmisto: **VMWare Workstation Pro**
- Virtuaalikoneet: **Debian 13 Trixie**(slave) ja **Kali Linux** (master)

Suosittelemme käyttämään Kali Linuxia, jos haluat kokeilla hyökkäystyökaluja, sillä niiden asennus esim. Debianille on paljon monimutkaisempaa (Kalissa valmiina paljon).

Saltin asennuksen jälkeen asetetaan Kali Debianin masteriksi muokkaamalla ```/etc/salt/minion```-tiedostoa Debian-koneella komennolla: ```$ sudo nano /etc/salt/minion```.  

<img width="619" height="413" alt="image" src="https://github.com/user-attachments/assets/1c4c5981-56ea-4f39-80cc-39ff96fd61d3" />

- Lisää rivi = master: (masterin_ip_osoite)
- Vapaaehtoisesti lisättävä rivi = id: (Slavekoneen-nimi)
  
Tässä vaiheessa käynnistetään salt-minion uudelleen komennoilla: ```$ sudo systemctl stop salt-minion``` ja ```$ sudo systemctl start salt-minion```.

Sitten siirrytään master-koneelle ja ajetaan komento: ```$ sudo salt-key -A```, jotta voidaan hyväksyä "slave".

<img width="306" height="104" alt="image" src="https://github.com/user-attachments/assets/0a74b391-0c4e-4593-bb6d-bc2e57d41b49" />

### Salt tilat

Aloitetaan kloonaamalla tämä Git-varasto komennolla:

    $ git clone https://github.com/Karjiss/palvelinhallintaprojekti.git

Seuraavaksi kopioidaan Salt-hakemisto omaan ```/srv/salt``` hakemistoon:

    $ sudo cp -r ~/palvelinhallintaprojekti/srv/salt/* /srv/salt/

Ajettiin tilat masterilta komennolla: 

    $ sudo salt '*' state.apply

<img width="544" height="609" alt="image" src="https://github.com/user-attachments/assets/472a067f-bbd2-40a7-824a-870ab2c2673b" />

-

  **Tulos:**

  <img width="955" height="489" alt="image" src="https://github.com/user-attachments/assets/80750629-11e6-4c5d-873c-d18d7f1bd1c1" />

  - Virtuaalikone on nyt valmis Kali Linuxin rääkättäväksi.
