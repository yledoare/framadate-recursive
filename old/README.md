# This repo contains docker files to install a recurive framadate

Update docker-compose.yml to set your domain
Updated apache port if needed

Run docker compose up -d

Add to your crontab :
docker exec framadate-app-recursive php /var/www/framadate/cron.php >>/tmp/cron.log 2>>/tmp/cron.log
