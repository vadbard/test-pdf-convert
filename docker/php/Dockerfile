FROM php:8.3-alpine as php

RUN apk add --no-cache --update \
        git \
        jpegoptim optipng pngquant gifsicle imagemagick \
        icu-dev \
        libtool \
        libzip-dev zlib-dev libpng-dev libwebp-dev libjpeg-turbo-dev freetype-dev

RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS imagemagick-dev \
    && pecl install imagick \
    && docker-php-ext-enable imagick \
    && apk del .build-deps

COPY --from=composer /usr/bin/composer /usr/local/bin/composer

USER www-data:www-data

WORKDIR /var/www/app

ENTRYPOINT ["php", "artisan", "optimize"]
