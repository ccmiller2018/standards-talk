# Offical Docker Image for PHP
# https://hub.docker.com/_/php
FROM php:8.0-fpm

# Set Current Directory
WORKDIR /var/www/vhost/

# Update System Dependencies
RUN apt-get clean && apt-get update --fix-missing && apt-get upgrade --yes

# Install System Dependencies
RUN apt-get install --yes \
    git \
    libcurl4-openssl-dev \
    libicu-dev \
    libonig-dev \
    libpng-dev \
    libssl-dev \
    libxml2-dev \
    libzip-dev \
    unzip \
    wget \
    zip

# PHP Extensions
# (ctype, fileinfo, json, mbstring, openssl, tokenizer and xml are already enabled)
# https://github.com/mlocati/docker-php-extension-installer
RUN docker-php-ext-install \
    bcmath \
    gd \
    intl \
    opcache \
    pdo_mysql \
    zip

# PHP Config
COPY ./config/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Install Composer from Official Docker Image
# https://hub.docker.com/_/composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
