FROM composer:1.9.0 as builder
WORKDIR /app/
COPY composer.* ./
RUN composer install

FROM php:8.1-apache
RUN apt-get update \
    && apt-get install -y \
       libzip-dev \
       unzip \
       zip \
       libpng-dev \
       libonig-dev \
       libxml2-dev \
       zlib1g-dev \
       libpq-dev \
       libzip-dev 
RUN pecl install xdebug; \
    docker-php-ext-enable xdebug; \
    echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
    echo "display_startup_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
    echo "display_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
    echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini;
RUN docker-php-ext-install \
       zip 
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
  && docker-php-ext-install pdo pdo_pgsql pgsql zip bcmath gd
RUN a2enmod rewrite
EXPOSE 8080
WORKDIR /var/www/html
COPY . ./
COPY --from=builder /app/vendor /var/www/html/vendor
COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf
RUN mkdir -p /var/www/html/bootstrap/cache \
    &&  chown -R www-data:www-data /var/www/html/bootstrap \
    && mkdir -p /var/www/html/storage/framework/sessions \
    && chown -R www-data:www-data /var/www/html/storage
