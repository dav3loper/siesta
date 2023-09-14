FROM composer:1.9.0 as builder
WORKDIR /app/
COPY composer.* ./
RUN composer install

FROM php:7.4.33-apache
RUN apt-get update \
    && apt-get install -y \
       libpng-dev \
       libonig-dev \
       libxml2-dev \
       zlib1g-dev \
       libpq-dev \
       libzip-dev
RUN docker-php-ext-install -j "$(nproc)" opcache
RUN set -ex; \
  { \
    echo "; Cloud Run enforces memory & timeouts"; \
    echo "memory_limit = -1"; \
    echo "max_execution_time = 0"; \
    echo "; File upload at Cloud Run network limit"; \
    echo "upload_max_filesize = 32M"; \
    echo "post_max_size = 32M"; \
    echo "; Configure Opcache for Containers"; \
    echo "opcache.enable = On"; \
    echo "opcache.validate_timestamps = Off"; \
    echo "; Configure Opcache Memory (Application-specific)"; \
    echo "opcache.memory_consumption = 32"; \
  } > "$PHP_INI_DIR/conf.d/cloud-run.ini"

RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
  && docker-php-ext-install pdo pdo_pgsql pgsql bcmath
EXPOSE 8080
WORKDIR /var/www/html
COPY . ./
COPY --from=builder /app/vendor /var/www/html/vendor
COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf
RUN mkdir -p /var/www/html/bootstrap/cache \
    &&  chown -R www-data:www-data /var/www/html/bootstrap \
    && mkdir -p /var/www/html/storage/framework/sessions \
    && chown -R www-data:www-data /var/www/html/storage
CMD ["apache2-foreground"]
