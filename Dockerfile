FROM php:7.4-apache
RUN apt-get update \
    && apt-get install -y \
       libzip-dev \
       unzip \
       zip
RUN docker-php-ext-install \
       zip \
       pdo_mysql
RUN a2enmod rewrite
RUN curl -sS https://getcomposer.org/installer | \
    php -- --install-dir=/usr/bin/ --filename=composer
WORKDIR /var/www
