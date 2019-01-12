FROM composer:latest

COPY ./composer.* /app/

WORKDIR /app
RUN composer install

RUN mkdir /var/openchat
WORKDIR /var/openchat

EXPOSE 80 3306 22
