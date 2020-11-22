FROM php:7.2-fpm

RUN apt-get update && apt-get install -y libmcrypt-dev \
    cron zip unzip git wget libpq-dev libpng-dev \
    zlib1g-dev \
    libzip-dev \
    && docker-php-ext-install pdo_pgsql exif zip gd \
    && echo "en_US.UTF-8 UTF-8" > /etc/locale.gen \
    && pecl install pcov && docker-php-ext-enable pcov

RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install mysqli