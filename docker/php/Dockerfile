FROM composer:latest

COPY ./composer.* ./app/

WORKDIR /app
RUN composer install

EXPOSE 80 3306 22
