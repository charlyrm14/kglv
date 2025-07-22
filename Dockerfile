# PHP, Composer, y extensiones necesarias
FROM php:8.3-fpm

# Dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libpng-dev \
    libpq-dev \
    libcurl4-openssl-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copia el código de la app al contenedor
COPY . /var/www/html
WORKDIR /var/www/html

# Dependencias de Laravel
RUN composer install --no-dev --optimize-autoloader \
    && php artisan config:cache \
    && php artisan route:cache

# Expone el puerto 8000
EXPOSE 8000

# Comando para correr Laravel
CMD php artisan serve --host=0.0.0.0 --port=8000
