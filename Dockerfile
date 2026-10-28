FROM php:8.2-cli-alpine

WORKDIR /var/www/html

# Install system dependencies and PHP extensions required by Laravel 12
RUN apk add --no-cache \
    curl \
    git \
    unzip \
    sqlite-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_sqlite bcmath gd zip opcache

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy project files
COPY . .

# Install production dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Setup environment, keys, database, and storage symlink
RUN if [ ! -f .env ]; then cp .env.example .env; fi && \
    php artisan key:generate --force && \
    mkdir -p database && \
    touch database/database.sqlite && \
    php artisan migrate --force --seed && \
    php artisan storage:link && \
    chmod -R 777 storage bootstrap/cache database

EXPOSE 8080

# Start server on dynamic host port assigned by Render/Koyeb ($PORT)
CMD php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
