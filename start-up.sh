#!/bin/bash
echo 'write environment variables..'
export APP_DEBUG=false
export APP_ENV=production
export APP_KEY=SomeRandomKey!!!
export DB_CONNECTION=mysql
export DB_HOST=message_server_mysql
export DB_PORT=3306
export DB_DATABASE=message_server
export DB_USERNAME=root
export DB_PASSWORD=$MYSQL_ENV_MYSQL_ROOT_PASSWORD
export CACHE_DRIVER=memcached
export QUEUE_DRIVER=sync
export APP_TIMEZONE=PRC
export DB_TIMEZONE=+08:00
echo 'change to workdir'
cd /var/www/webapp;
echo "start migrate"
php artisan migrate --force
echo "migrate finshed"

echo 'start apache..'
a2enmod rewrite
apache2-foreground
