# Используем кастомный PHP CLI образ, который ты уже собрал
FROM php:8.4.12-fpm

ENV DEBIAN_FRONTEND=noninteractive

ARG PUID=1000
ARG PGID=1000


# Установка системных зависимостей
RUN apt-get update && apt-get install -y \
    locales \
    unzip \
    zip \
    git \
    curl \
    nano \
    tree \
    libzip-dev \
    libpq-dev \
    libjpeg-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libssl-dev \
    libsqlite3-dev \
    libicu-dev \
    libcurl4-openssl-dev \
    libedit-dev \
    libmemcached-dev \
    zlib1g-dev \
    pkg-config \
    postgresql-client \
    && locale-gen en_US.UTF-8

# Установка PHP расширений через docker-php-ext-install
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    pgsql \
    mysqli \
    bcmath \
    intl \
    xml \
    mbstring \
    zip \
    opcache \
    sockets \
    pcntl \
    exif \
    gd

# Установка memcached (через PECL)
RUN pecl install memcached && docker-php-ext-enable memcached

RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

RUN set -eux \
    && groupadd -g 1000 laradock \
    && groupadd docker_env \
    && useradd -l -u 1000 -g laradock -m laradock -G docker_env \
    && usermod -p "*" laradock -s /bin/bash \
    && ln -snf /usr/share/zoneinfo/UTC /etc/localtime \
    && echo UTC > /etc/timezone


# Установка дополнительных системных пакетов и Composer обновление
RUN apt-get update && apt-get install -y --no-install-recommends \
    apt-utils \
    libzip-dev \
    zip \
    unzip \
    nasm \
    libxrender1 \
    libfontconfig1 \
    libx11-dev \
    libjpeg62 \
    libxtst6 \
    fontconfig \
    libjpeg-dev \
    xfonts-base \
    xfonts-75dpi \
    potrace \
    wget \
    imagemagick \
    libmagickwand-dev \
    git \
    && composer self-update --2

# Установка PECL расширений (redis, igbinary)
RUN pecl install redis igbinary \
    && docker-php-ext-enable redis igbinary

# Сборка и установка Imagick вручную
RUN git clone https://github.com/Imagick/imagick /tmp/imagick \
    && cd /tmp/imagick \
    && phpize \
    && ./configure \
    && make \
    && make install \
    && echo "extension=imagick.so" > /usr/local/etc/php/conf.d/imagick.ini \
    && php -m | grep -q 'imagick' \
    && rm -rf /tmp/imagick

# Очистка
RUN apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* && \
    rm -f /var/log/lastlog /var/log/faillog

# Рабочая директория
WORKDIR /var/www


RUN groupmod -o -g ${PGID} www-data && \
    usermod -o -u ${PUID} -g www-data www-data

ENV LC_ALL POSIX

WORKDIR /etc/supervisor/conf.d/
