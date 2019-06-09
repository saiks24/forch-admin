FROM php:7.1-fpm

# Install recommended extensions for Symfony
RUN apt-get update && apt-get install -y \
        libicu-dev \
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

RUN curl -sS https://getcomposer.org/installer | php \
    &&mv composer.phar /usr/local/bin/composer \
    &&ln -s /usr/local/bin/composer /usr/bin/composer