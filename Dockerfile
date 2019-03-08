FROM php:7.1

MAINTAINER Zane Lamb zanelamb@live.com

RUN apt-get update && apt-get install -y graphviz \
    && rm -rf /var/lib/apt/lists/*

# Install mysqli extension
RUN docker-php-ext-install mysqli
# Install xdebug and php extension for xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug