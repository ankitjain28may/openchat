FROM php:7.3-fpm

RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
    && docker-php-ext-install -j$(nproc) iconv \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd


# Cleanup
RUN apt-get remove --purge -y software-properties-common \
    python-software-properties && \
    apt-get autoremove -y && \
    apt-get clean && \
    apt-get autoclean && \
    # install composer
    curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer


COPY ./composer.* /app/

WORKDIR /app
RUN composer install

RUN mkdir /var/openchat
WORKDIR /var/openchat

EXPOSE 80 3306 22
