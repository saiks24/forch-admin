FROM php:7.2-fpm

RUN apt-get update
RUN apt-get install -y libicu-dev


RUN docker-php-ext-install intl
RUN docker-php-ext-install pdo
RUN docker-php-ext-enable pdo
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-enable pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN apt-get install git -y