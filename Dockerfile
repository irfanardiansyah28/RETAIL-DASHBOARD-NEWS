FROM php:8.4-cli

# Allow composer root
ENV COMPOSER_ALLOW_SUPERUSER=1

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    zip \
    libzip-dev \
    nodejs \
    npm \
    && docker-php-ext-install pdo pdo_mysql zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy project
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install Node dependencies
RUN npm install

# Build frontend
RUN npm run build

# Laravel optimize
RUN php artisan config:clear
RUN php artisan cache:clear

# Railway port
EXPOSE 8080

# Start app
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=8080