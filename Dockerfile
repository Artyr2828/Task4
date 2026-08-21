FROM php:8.4-apache


RUN apt-get update && apt-get install -y \
    libpq-dev \
    libicu-dev \
    libzip-dev \
    git \
    unzip \
    && docker-php-ext-install pdo pdo_pgsql intl zip opcache

RUN a2enmod rewrite
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV APP_ENV=prod
RUN composer install --no-dev --optimize-autoloader --no-interaction

RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/0000-default.conf


RUN chown -R www-data:www-data /var/www/html/var
EXPOSE 80

CMD php bin/console doctrine:migrations:migrate --no-interaction && apache2-foreground
