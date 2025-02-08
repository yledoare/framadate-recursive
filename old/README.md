# This repo contains docker files to install a recurive framadate

Update docker-compose.yml to set your domain
Updated apache port if needed

Run docker compose up -d

Add to your crontab :
docker exec framadate-app-recursive php /var/www/framadate/cron.php >>/tmp/cron.log 2>>/tmp/cron.log

# Manual fix

app/inc/i18n.php : In date_format_intl, force locale with $local_locale = $locale;
app/inc/config.php : if https enables, set const FORCE_HTTPS = true;

# Fix mail sender

+++ b/app/inc/services.php
@@ -51,9 +51,15 @@ class Services {
     }
 
     public static function mail() {
+       global $config;
         if (self::$mailService === null) {

# Fix bad error when updating own votes

docker cp app/classes/Framadate/Services/PollService.php framadate-app-recursive:app/classes/Framadate/Services/PollService.php
