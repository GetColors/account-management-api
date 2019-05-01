FROM composer:1.8 AS vendor

COPY composer.json composer.lock /app/

RUN composer install \
     --ignore-platform-reqs \
        --no-interaction \
        --no-plugins \
        --no-scripts \
        --prefer-dist

FROM php:7.2-fpm-alpine3.9

WORKDIR /app

RUN set -ex \
  && apk --no-cache add \
    postgresql-dev

RUN docker-php-ext-install sockets mbstring bcmath pdo pgsql pdo_pgsql

COPY . /var/www/html/app
COPY --from=vendor ./app/vendor/ /var/www/html/app/vendor/
COPY ./docker/php-fpm/php-ini-overrides.ini /etc/php/7.0/fpm/conf.d/99-overrides.ini

EXPOSE 80