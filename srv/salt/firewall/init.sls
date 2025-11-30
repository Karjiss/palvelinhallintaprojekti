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
