FROM php:8.2-fpm-alpine

WORKDIR /var/www/html

# Install system dependencies
RUN apk add --no-cache \
    sqlite \
    sqlite-dev \
    libxml2-dev \
    git

RUN docker-php-ext-install pdo_sqlite dom

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

RUN mkdir -p /var/www/html/storage/app/public \
    && mkdir -p /var/www/html/storage/framework/{cache,sessions,views} \
    && mkdir -p /var/www/html/storage/logs \
    && mkdir -p /var/www/html/bootstrap/cache \
    && touch /var/www/html/database/database.sqlite

RUN chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/database

RUN touch /var/www/html/database/database.sqlite

RUN composer install --no-interaction --no-dev --optimize-autoloader

RUN php artisan key:generate
RUN php artisan migrate

ENTRYPOINT ["php", "artisan"]
