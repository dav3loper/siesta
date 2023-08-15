FROM composer:1.9.0 as builder
WORKDIR /app/
COPY composer.* ./
RUN composer install

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
    echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini;
RUN docker-php-ext-install \
       zip \
       pdo_mysql
RUN a2enmod rewrite
RUN curl -sS https://getcomposer.org/installer | \
    php -- --install-dir=/usr/bin/ --filename=composer
EXPOSE 8080
WORKDIR /var/www/html
COPY . ./
COPY --from=builder /app/vendor /var/www/vendor
COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf
