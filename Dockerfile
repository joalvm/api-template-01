FROM php:8.2-apache

LABEL maintainer="Alejandro Vilchez"

ARG APP_NAME
ARG WWWGROUP
ARG WWWUSER
ARG FILESYSTEM_ROOT

# Set working directory
WORKDIR /var/www/html

ENV DEBIAN_FRONTEND noninteractive
ENV TZ=UTC

# Timezone UTC
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Actualizar servidor
RUN apt update -y && apt upgrade -y

# Instalar dependencias del servidor.
RUN apt install -y --no-install-recommends \
    zip \
    git \
    nano \
    sudo \
    curl \
    unzip \
    autoconf \
    apt-utils \
    libpq-dev \
    libzip-dev \
    libicu-dev \
    libpng-dev \
    libxpm-dev \
    libvpx-dev \
    supervisor \
    libbz2-dev \
    libgd-dev \
    libmcrypt-dev \
    build-essential \
    ca-certificates \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libmagickwand-dev \
    libmagickcore-dev

# Instalar Modulos de PHP
RUN docker-php-ext-install -j$(nproc) \
    bz2 \
    intl \
    iconv \
    bcmath \
    opcache \
    calendar \
    pdo_pgsql \
    zip \
    && docker-php-ext-configure gd \
    --with-jpeg \
    --with-freetype \
    --with-xpm=/usr/lib/x86_64-linux-gnu/

RUN docker-php-ext-install -j$(nproc) gd

# Instalar mgick
RUN pecl install imagick && docker-php-ext-enable imagick && \
    rm -rf /tmp/pear

RUN php -r "readfile('https://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer

# Limpiar lo descargado en la actualización.
RUN apt -y autoremove && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# mod_rewrite for URL rewrite and mod_headers for .htaccess extra headers like Access-Control-Allow-Origin-
RUN a2enmod rewrite headers expires unique_id

# mpm_event to mpm_prefork
RUN a2dismod mpm_event && a2enmod mpm_prefork

# Instalar supervisor
RUN apt-get update && apt-get install -y supervisor

# RUN setcap "cap_net_bind_service=+ep" /usr/bin/php

RUN groupadd -g $WWWGROUP $WWWUSER
RUN useradd -M -u $WWWGROUP -g $WWWGROUP $WWWUSER -s /sbin/nologin

COPY .docker/apache/vhost.conf /etc/apache2/sites-available/api.host.local.conf

# Cambiar ${APP_NAME} por el nombre de la aplicación en el archivo api.host.local.conf
RUN sed -i "s/\${APP_NAME}/${APP_NAME}/g" /etc/apache2/sites-available/api.host.local.conf

RUN a2ensite api.host.local.conf

COPY .docker/php/99-custom.ini /usr/local/etc/php/conf.d/99-custom.ini

RUN mkdir -p $FILESYSTEM_ROOT && \
    chown -R www-data:www-data $FILESYSTEM_ROOT && \
    chmod -R 775 $FILESYSTEM_ROOT

EXPOSE 80
