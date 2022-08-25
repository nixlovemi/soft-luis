FROM php:7.4.29-apache

RUN apt-get update

# Install Mysql Drivers
RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable pdo_mysql

# install GD
RUN apt-get install -y \
    libwebp-dev \
    libjpeg62-turbo-dev \
    libpng-dev libxpm-dev \
    libfreetype6-dev

RUN docker-php-ext-configure gd \
      --with-jpeg=/usr/include/ \
      --with-freetype=/usr/include/

RUN docker-php-ext-install gd
# ==========

# mod rewrite
RUN a2enmod rewrite