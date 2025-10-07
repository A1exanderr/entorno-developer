FROM php:8.2-fpm
# Instalar dependencias del sistema
RUN apt-get update && \
    apt-get install -y curl unzip git libpq-dev libzip-dev libgmp-dev zip gnupg && \
    curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs && \
    npm install -g pm2 web-push && \
    docker-php-ext-install pdo pdo_pgsql gmp bcmath

# Copiar Composer desde la imagen oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
# Instalar Laravel Installer globalmente
RUN composer global require laravel/installer && \
    ln -s /root/.composer/vendor/bin/laravel /usr/local/bin/laravel
# Establecer directorio de trabajo
WORKDIR /var/www/html
EXPOSE 8000
# Mantener el contenedor vivo para desarrollo
CMD ["tail", "-f", "/dev/null"]