FROM php:8.0-fpm

RUN apt-get update && \
    apt-get install -y
RUN apt-get install -y curl
RUN apt-get install -y build-essential libonig-dev libzip-dev libssl-dev libpng-dev libjpeg-dev libfreetype6-dev
RUN apt-get install -y libicu-dev
RUN apt-get update
RUN docker-php-ext-install intl
RUN docker-php-ext-configure intl
RUN docker-php-ext-install mysqli pdo pdo_mysql zip mbstring
COPY --from=composer /usr/bin/composer /usr/bin/composer
EXPOSE 9000