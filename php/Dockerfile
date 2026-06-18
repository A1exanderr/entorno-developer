FROM php:8.2-fpm
# Instalar dependencias del sistema
RUN apt-get update && \
    apt-get install -y curl unzip git libpq-dev libzip-dev libgmp-dev zip gnupg && \
    curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs && \
    npm install -g pm2 web-push && \
    docker-php-ext-install pdo pdo_pgsql gmp bcmath
# Establecer directorio de trabajo
WORKDIR /var/www/html
# Copiar Composer desde la imagen oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
# Copiar entrypoint
COPY ./docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh
# Comando por defecto
ENTRYPOINT ["docker-entrypoint.sh"]
