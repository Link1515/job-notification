FROM php:8.2-cli-alpine AS base

RUN apk add --no-cache \
        sqlite-libs \
        zlib \
    && apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS \
        sqlite-dev \
        zlib-dev \
    && docker-php-ext-install pdo pdo_sqlite \
    && apk del .build-deps

FROM base AS build

RUN apk add --no-cache \
        git \
        unzip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json composer.lock* ./
RUN --mount=type=cache,target=/tmp/composer-cache \
    COMPOSER_CACHE_DIR=/tmp/composer-cache \
    composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-progress

COPY . .

FROM base

WORKDIR /app
COPY --from=build /app /app

CMD ["php", "run.php"]
