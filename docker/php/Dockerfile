FROM composer:latest

RUN apt-get update && apt-get install -y supervisor
RUN mkdir -p /var/log/supervisor

COPY ./composer.* ./app/

WORKDIR /app
RUN composer install

EXPOSE 80 3306 22
