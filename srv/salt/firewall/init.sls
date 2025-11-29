ufw-package:
  pkg.installed:
    - name: ufw
ufw-allow-http:
  cmd.run:
    - name: 'sudo ufw allow 80/tcp'
    - unless: 'sudo ufw status | grep "80/tcp"'
ufw-enable:
  cmd.run:
    - name: 'ufw --force enable'
    - name: 'ufw status | grep -i "active"'
show-ufw-status:
  cmd.run:
    - name: 'sudo ufw status'
