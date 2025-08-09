FROM node:22 AS node
FROM composer:2 AS composer
FROM php:8.4.11-fpm

RUN apt update && apt install -y git \
		libzip-dev \
        zip \
        unzip \
  		&& docker-php-ext-install zip

RUN docker-php-ext-install pdo

RUN curl -L https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/releases/download/v3.85.1/php-cs-fixer.phar -o php-cs-fixer && \
	chmod a+x php-cs-fixer && \
	mv php-cs-fixer /usr/local/bin/php-cs-fixer

# Composer
COPY --from=composer /usr/bin/composer /usr/local/bin/composer

# Node and npm
COPY --from=node /usr/lib /usr/lib
COPY --from=node /usr/local/lib /usr/local/lib
COPY --from=node /usr/local/include /usr/local/include
COPY --from=node /usr/local/bin /usr/local/bin

# Clean up image
RUN rm -rf /tmp/* /var/cache

ARG USER_UID=82
ARG USER_GID=82

ENV SHELL=/bin/bash

# Recreate www-data user with user id matching the host
RUN deluser --remove-home www-data && \
    addgroup --system --gid ${USER_GID} www-data && \
    adduser --uid ${USER_UID} --system --home /home/www-data --ingroup www-data www-data

USER www-data

# Laravel installer
RUN composer global require laravel/installer
RUN echo 'export PATH=~/.composer/vendor/bin:$PATH' > ~/.bashrc

WORKDIR /var/www/html