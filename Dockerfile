FROM php:8.4-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip \
    libprotobuf-dev protobuf-compiler \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install gRPC
RUN pecl install grpc && docker-php-ext-enable grpc

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/sunrise/gateway

COPY . .
	
# Install composer globally
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

# Install PHP dependencies
RUN composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction

EXPOSE 9000
CMD ["php-fpm"]
