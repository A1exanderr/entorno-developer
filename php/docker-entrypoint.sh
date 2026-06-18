#!/bin/bash

# Asignar permisos necesarios para Laravel
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

#composer install
#php artisan migrate:refresh --seed

# Ejecutar desde el host (como hiciste antes)
#sudo chown -R $USER:www-data storage bootstrap/cache
#chmod -R 775 storage bootstrap/cache
# Limpiar y volver a enlazar el storage

rm -rf /var/www/html/public/storage
php artisan storage:link
# Asegurar permisos públicos (con cuidado)
#chmod 777 -R /var/www/html/storage/
chmod 777 -R /var/www/html/public/
chown www-data -R /var/www/html/public/
#chown www-data -R /var/www/html/storage/
# Ejecutar el comando principal del contenedor (PHP-FPM)
exec php-fpm
#exec "$@"