FROM php:8.3-apache

WORKDIR /var/www

RUN apt-get update -yqq && \
    apt-get install -yqq vim git exiftool && \
    docker-php-ext-install exif && \
    docker-php-ext-enable exif && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY php-custom.ini /usr/local/etc/php/conf.d/php-custom.ini

RUN a2enmod rewrite \
    && service apache2 restart

EXPOSE 80