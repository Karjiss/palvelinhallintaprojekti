#!/bin/bash
sudo salt 'work.work' cmd.run 'cat /tmp/scan.txt' > /srv/salt/reports/raportti.txt
echo "Raportti haettu!"
