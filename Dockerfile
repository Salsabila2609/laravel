# Gunakan PHP 8.2 sebagai base image
FROM php:8.2-fpm

# Install dependensi dasar
RUN apt-get update && apt-get install -y \
    zip unzip git curl \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js dan npm
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

# Set working directory
WORKDIR /var/www

# Copy semua file Laravel ke dalam container
COPY . .

# Atur izin supaya Laravel bisa berjalan
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Copy entrypoint script ke dalam container
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Gunakan entrypoint script
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]

# Jalankan PHP-FPM setelah entrypoint selesai
CMD ["php-fpm"]
