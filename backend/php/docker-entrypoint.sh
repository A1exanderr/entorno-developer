#!/bin/bash
set -e

APP_DIR="/var/www/html"

setup_laravel()
{
    # Asignar permisos necesarios
    chown -R www-data:www-data $APP_DIR/storage $APP_DIR/bootstrap/cache
    chmod -R 775 $APP_DIR/storage $APP_DIR/bootstrap/cache

    # Instalar dependencias PHP si hace falta
    if [ -f "$APP_DIR/composer.json" ]; then
        composer install --prefer-dist --no-interaction --optimize-autoloader
    fi

    # Volver a enlazar storage si hace falta
    if [ ! -L "$APP_DIR/public/storage" ]; then
        echo "Creando enlace simbólico storage..."
        php $APP_DIR/artisan storage:link || true
    fi

    # Asegurar permisos en la carpeta public
    chmod -R 777 $APP_DIR/public
    chown -R www-data:www-data $APP_DIR/public

    # Ejecutar migraciones automáticas si existe `.env`
    if [ -f "$APP_DIR/.env" ]; then
        echo "Ejecutando migraciones..."
        php $APP_DIR/artisan migrate --force || true
    fi
}
# Detectar si la carpeta está vacía
if [ -z "$(ls -A $APP_DIR)" ]; then
    echo "No se encontró un proyecto Laravel. Creando uno nuevo..."
    
    # Instalar Laravel global si no existe
    #if ! command -v laravel >/dev/null 2>&1; then
    #    composer global require laravel/installer
    #    #export PATH="$HOME/.composer/vendor/bin:$PATH"
    #    ln -s /root/.composer/vendor/bin/laravel /usr/local/bin/laravel
    #fi

    # Crear proyecto Laravel en el directorio de trabajo
    #laravel new laravel
    #laravel new .

    # Generar la key de aplicación
    #php $APP_DIR/laravel/artisan key:generate
    #php artisan key:generate
    composer create-project --prefer-dist laravel/laravel .
    php artisan key:generate
    setup_laravel
else
    echo "Proyecto Laravel existente detectado. Usándolo..."
    setup_laravel
fi

# Iniciar PHP-FPM
exec php-fpm