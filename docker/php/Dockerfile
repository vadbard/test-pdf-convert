FROM php:8.3.2-alpine3.19 as php

RUN apk add --no-cache --update \
        imagemagick ghostscript-fonts ghostscript

RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS imagemagick-dev \
    && pecl install imagick \
    && docker-php-ext-enable imagick \
    && apk del .build-deps

COPY --from=composer /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www/app
