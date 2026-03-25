FROM php:8.4-apache

RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libicu-dev \
    libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql zip mbstring intl

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf

RUN a2enmod rewrite

RUN printf '<Directory /var/www/html/public>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>\n' > /etc/apache2/conf-available/symfony.conf \
    && a2enconf symfony

WORKDIR /var/www/html

COPY . .

RUN git config --global --add safe.directory /var/www/html

RUN composer install --no-interaction --optimize-autoloader

RUN chown -R www-data:www-data /var/www/html/var /var/www/html/public