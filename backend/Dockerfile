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
COPY docker/php/conf.d/uploads.ini /usr/local/etc/php/conf.d/uploads.ini

# Install deps Symfony
RUN composer install --no-interaction

RUN mkdir -p /var/www/html/public/uploads/clinical-cases \
    && chown -R www-data:www-data /var/www/html/var /var/www/html/public/uploads \
    && chmod -R 775 /var/www/html/public/uploads

CMD ["php-fpm"]

