cp /usr/src/parltrack-tools/ep_mep_active.json .
php json2csv.php
php companies.php
q "select c.*, e.first_name,e.last_name,country,eugroup from companies.csv c join ep_meps_current.csv e on c.mep_id=e.epid" -d, -H -O > full_companies.csv

cp doi.json /var/www/doi/doi-pretty.json 
#cp /usr/src/parltrack-tools/ep_meps_current.csv /var/www/doi/mep.csv
cp /usr/src/parltrack-tools/ep_mep_active.csv /var/www/doi/mep.csv


