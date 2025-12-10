FROM php:8.4-fpm

# Extensions système
RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libpq-dev libonig-dev libzip-dev \
    && docker-php-ext-install intl pdo pdo_mysql opcache zip

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Dossier de travail
WORKDIR /var/www/html

# Copier les fichiers (on affinera avec docker-compose pour le volume)
COPY . .

# Install deps Symfony
RUN composer install --no-interaction

# Donner les bons droits
RUN chown -R www-data:www-data /var/www/html/var

CMD ["php-fpm"]

