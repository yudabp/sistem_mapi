FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy existing application directory contents
COPY . /var/www

# Copy custom configurations
COPY .docker/php.ini /usr/local/etc/php/conf.d/custom.ini

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader

# Install NPM dependencies
RUN npm install && npm run build

# Change ownership of files
RUN chown -R www-data:www-data /var/www

EXPOSE 9000

CMD ["php-fpm"]