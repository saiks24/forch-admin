FROM php:7.1-fpm

# Install recommended extensions for Symfony
RUN apt-get update && apt-get install -y \
        libicu-dev \
        mysql-client \
    && docker-php-ext-install \
        intl \
        opcache \
        pdo \
        pdo_mysql \
    && docker-php-ext-enable \
        intl \
        opcache \
        pdo \
        pdo_mysql \