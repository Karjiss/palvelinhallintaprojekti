apache2:
  pkg.installed
/var/www/html/index.html:
  file.managed:
    - source: "salt://apache/index.html"
/var/www/html/haavoittuvuus.php:
  file.managed:
    - source: "salt://apache/haavoittuvuus.php"
apache2.service:
  service.running:
    - watch:
      - file: /var/www/html/index.html
