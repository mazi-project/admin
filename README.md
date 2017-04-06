# [DEPRECATED] admin
This is the OLD admin interface of the MAZI toolkit.

Please use the new portal for the MAZI toolkit https://github.com/mazi-project/portal

# Installation
For the scripts driving the admin page to work, the apache user (www-data) must be given sudo capibilities
on them.

This can be achieved by adding the line:

www-data  ALL=NOPASSWD: /var/www/html/adminpage/scripts/wifiap.sh, /var/www/html/adminpage/scripts/internet.sh

in visodo

If this fails, 
www-data  ALL=NOPASSWD:ALL
Can be used as an insecure alternative for testing

A change was made to scripts/current .sh to maintain consistent output. the lines
74: else
75: echo "password"
were added so that a placeholder for password is always present.

Application Admin:
This page allows the administrator to select which applications will show on the splash page, the generates it.

The applications which are available should be recorded in applications.csv
in the /db folder in the format name, hyperlink

On page generation, the templates header.html and footer.html are combined,
with each chosen application embedded between them as a formatted link. They are then saved as splash.html

php MUST have write access to splash.html in the /applicationpage folder
