# admin

For the scripts driving the admin page to work, the apache user (www-data) must be given sudo capibilities
on them.

This can be achieved by adding the line:

www-data  ALL=NOPASSWD: /var/www/html/adminpage/scripts/wifiap.sh, /var/www/html/adminpage/scripts/internet.sh

in visodo

If this fails, 
www-data  ALL=NOPASSWD:ALL
Can be used as an insecure alternative for testing