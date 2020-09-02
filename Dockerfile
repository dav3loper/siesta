FROM php:7.4-apache
RUN apt-get update \
    && apt-get install -y \
       libzip-dev \
       unzip \
       zip
RUN pecl install xdebug; \
    docker-php-ext-enable xdebug; \
    echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
    echo "display_startup_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
    echo "display_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
    echo "xdebug.remote_enable=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini;
RUN docker-php-ext-install \
       zip \
       pdo_mysql
RUN a2enmod rewrite
RUN curl -sS https://getcomposer.org/installer | \
    php -- --install-dir=/usr/bin/ --filename=composer
WORKDIR /var/www
