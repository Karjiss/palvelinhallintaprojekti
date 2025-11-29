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
      - file: /var/www/html/index.html
