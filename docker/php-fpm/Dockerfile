FROM php:7.4.0-fpm

RUN apt-get update

RUN pecl install xdebug-3.1.5

RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN docker-php-ext-install pcntl

RUN apt-get install -y libmcrypt-dev libpq-dev

RUN pecl install mcrypt-1.0.5
RUN docker-php-ext-enable mcrypt

RUN apt-get install -y libzip-dev
RUN docker-php-ext-install zip

RUN apt-get install -y libicu-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt install vim -y

COPY user-config.ini $PHP_INI_DIR/conf.d/
COPY xdebug.ini $PHP_INI_DIR/conf.d/

