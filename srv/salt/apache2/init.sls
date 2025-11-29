apache2:
  pkg.installed
/var/www/html/index.html:
  file.managed:
    - source: "salt://apache2/index.html"
