FROM php:8.2-apache

# Установка зависимостей
RUN apt-get update && apt-get install -y \
    unzip \
    curl \
    libzip-dev \
    zip \
    sqlite3 \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite zip

# Включим mod_rewrite
RUN a2enmod rewrite

# Установка Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Копируем проект
COPY . /var/www/html
WORKDIR /var/www/html

# Установим public как DocumentRoot
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Установка зависимостей Laravel
RUN composer install --optimize-autoloader --no-dev

# Настройки прав
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chown -R www-data:www-data /var/www/html/database

# Открытый порт (Apache)
EXPOSE 80

# Запускаем Apache
CMD ["apache2-foreground"]
