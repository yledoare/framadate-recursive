# This repo contains docker files to install a recurive framadate

Add to your crontab :

docker exec framadate-app-recursive php /var/www/framadate/cron.php >>/tmp/cron.log 2>>/tmp/cron.log
