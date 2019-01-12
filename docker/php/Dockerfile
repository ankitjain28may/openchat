FROM php:7.3-fpm
LABEL maintainer="ankitjain28may77@gmail."
RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
    && docker-php-ext-install -j$(nproc) iconv \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install bcmath bz2 calendar intl mbstring mysqli opcache pdo_mysql pdo_pgsql pgsql soap \

RUN apt-get update && \
    apt-get install -y --no-install-recommends git zip

# Cleanup
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


COPY ./composer.* /app/

WORKDIR /app
RUN composer install

RUN mkdir /var/openchat
WORKDIR /var/openchat

EXPOSE 80 3306 22
