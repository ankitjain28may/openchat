#
# PHP dependencies stage
#
FROM composer as composer

WORKDIR /app

COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install --no-interaction --no-scripts

#
# Javascript dependencies stage
#
FROM node:12 as javascript

WORKDIR /app

COPY package*.json ./

# Install Javascript dependencies
RUN npm install

#
# App stage
#
FROM php:7.3-fpm-alpine

# Install dependencies
RUN apk --update add --virtual build-dependencies build-base openssl-dev autoconf \
    && docker-php-ext-install pdo pdo_mysql mysqli \
    && docker-php-ext-enable mysqli pdo pdo_mysql mysqli \
    && apk del build-dependencies build-base openssl-dev autoconf \
    && rm -rf /var/cache/apk/*

# Set the working directory
WORKDIR /var/www/html

# Copy vendor folder from previous stage
COPY --from=composer /app/vendor /var/www/html/vendor

# Copy node_modules folder from previous stage
COPY --from=javascript /app/node_modules /var/www/html/node_modules
