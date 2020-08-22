FROM php:7.4-apache
RUN apt-get update \
    && apt-get install -y \
       libzip-dev \
       unzip \
       zip
RUN docker-php-ext-install \
       zip 
RUN a2enmod rewrite
RUN curl -sS https://getcomposer.org/installer | \
    php -- --install-dir=/usr/bin/ --filename=composer
COPY . /var/www/html/
COPY docker/siesta.conf /etc/apache2/sites-enabled
RUN chown -R www-data:www-data /var/www
EXPOSE 80
CMD apache2-foreground
