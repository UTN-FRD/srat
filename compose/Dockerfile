FROM php:7.4.33-apache

ARG DEBIAN_FRONTEND=noninteractive
ARG MYSQL_DATABASE
ARG MYSQL_PASSWORD
ARG SECURITY_SALT
ARG SECURITY_SEED

COPY ./conf/000-default.conf /etc/apache2/sites-available/

WORKDIR /var/www/html

RUN apt-get update \
    && apt-get install -y libzip-dev \
    && apt-get install -y zlib1g-dev \
    && apt-get install -y libxext6 unzip fontconfig fontconfig-config fonts-dejavu-core libfontconfig1 \
        libfontenc1 libfreetype6 libjpeg62-turbo libpng16-16 libxrender1 sensible-utils ucf x11-common \
        xfonts-75dpi xfonts-base xfonts-encodings xfonts-utils \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install zip mysqli pdo pdo_mysql

RUN pecl install apcu \
    && pecl install apcu_bc-1.0.3 \
    && docker-php-ext-enable apcu --ini-name 10-docker-php-ext-apcu.ini \
    && docker-php-ext-enable apc --ini-name 20-docker-php-ext-apc.ini

RUN curl -SLOJ https://github.com/wkhtmltopdf/packaging/releases/download/0.12.6.1-2/wkhtmltox_0.12.6.1-2.bullseye_amd64.deb \
    && dpkg -i wkhtmltox_0.12.6.1-2.bullseye_amd64.deb \
    && rm -rf wkhtmltox_0.12.6.1-2.bullseye_amd64.deb

RUN curl -sSLOJ https://github.com/UTN-FRD/srat/releases/download/1.3.4/srat-1-3-4.zip \
    && unzip -q srat-1-3-4.zip \
    && rm -rf srat-1-3-4.zip \
    && chmod +x ./app/Console/cake

RUN sed -i "s/'database' => ''/'database' => '${MYSQL_DATABASE}'/g" ./app/Config/database.php
RUN sed -i "s/'host' => ''/'host' => 'db'/g" ./app/Config/database.php
RUN sed -i "s/'login' => ''/'login' => 'root'/g" ./app/Config/database.php
RUN sed -i "s/'password' => ''/'password' => '${MYSQL_PASSWORD}'/g" ./app/Config/database.php

RUN sed -i "s/'Security.salt', ''/'Security.salt', '${SECURITY_SALT}'/g" ./app/Config/core.php
RUN sed -i "s/'Security.cipherSeed', ''/'Security.cipherSeed', '${SECURITY_SEED}'/g" ./app/Config/core.php

RUN chown -R www-data:www-data ./app/tmp \
    && chmod -R g+w ./app/tmp

RUN chmod +x ./app/Console/cake

RUN a2enmod rewrite