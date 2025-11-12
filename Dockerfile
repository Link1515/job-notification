FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
        git \
        unzip \
        libsqlite3-dev \
        zlib1g-dev \
    && docker-php-ext-install pdo pdo_sqlite \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json composer.lock* ./
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

COPY . .

CMD ["php", "run.php"]