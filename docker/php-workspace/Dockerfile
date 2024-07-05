ARG DOCKER_PHP_VERSION

FROM php:${DOCKER_PHP_VERSION}-fpm

ARG TZ='UTC'

USER root

RUN apt-get update && apt-get install -y \
        libzip-dev \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        libonig-dev \
        mariadb-client \
        curl \
        vim \
        unzip \
        wget \
        && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd \
      --with-freetype=/usr/include/ \
      --with-jpeg=/usr/include/ && \
    docker-php-ext-install \
        gd \
        mysqli \
        pdo_mysql \
        zip \
        bcmath \
        opcache

RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

WORKDIR /var/www/test/backend

USER www-data:www-data
