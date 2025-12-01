apache2:
  pkg.installed
/var/www/html/index.html:
  file.managed:
    - source: "salt://apache2/index.html"
/var/www/html/vulnerable.php:
  file.managed:
    - source: "salt://apache2/vulnerable.php"
/var/www/html/static_vulnerable.html:
  file.managed:
    - source: "salt://apache2/static_vulnerable.html"
/var/www/html/secret:
  file.directory:
    - makedirs: True
/var/www/html/secret/passwrds.txt:
  file.managed:
    - source: "salt://apache2/secret/passwrds.txt"
apache2.service:
  service.running:
    - watch:
      - file: /var/www/html/index.html
      - file: /var/www/html/vulnerable.php
      - file: /var/www/html/static_vulnerable.html
      - file: /var/www/html/secret/passwrds.txt
