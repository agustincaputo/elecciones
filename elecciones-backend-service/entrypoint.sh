#!/bin/sh

if [ ! -f /usr/local/bin/wait-for-it ]; then
  curl -sSL https://raw.githubusercontent.com/vishnubob/wait-for-it/master/wait-for-it.sh -o /usr/local/bin/wait-for-it
  chmod +x /usr/local/bin/wait-for-it
fi

cd /var/www

chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

wait-for-it db:3306 --timeout=60 --strict -- echo "Base de datos lista"

if [ ! -d "vendor" ]; then
  echo "Instalando dependencias con Composer..."
  composer install --no-interaction --prefer-dist --optimize-autoloader
fi

php artisan migrate --force

exec php-fpm
