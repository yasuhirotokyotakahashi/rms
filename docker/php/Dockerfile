FROM php:7.4.9-fpm

RUN mkdir -p /usr/local/etc/php/

COPY php.ini /usr/local/etc/php/
COPY php-fpm.conf /usr/local/etc/php-fpm.conf



RUN apt-get update && apt-get install -y default-mysql-client
RUN apt-get install -y \ 
  zlib1g-dev \
  libzip-dev \
  unzip libpng-dev \
  libjpeg-dev \
  libfreetype6-dev \
  libwebp-dev \
  libxpm-dev \
  zip \
  libxml2-dev

RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp --with-xpm
RUN docker-php-ext-install gd 
RUN docker-php-ext-install pdo_mysql
RUN zip
RUN docker-php-ext-install mbstring 
RUN docker-php-ext-install dom

RUN curl -sS https://getcomposer.org/installer | php \
  && mv composer.phar /usr/local/bin/composer \
  && composer self-update

WORKDIR /var/www


FROM amazonlinux:2

# 必要なパッケージのインストール
RUN yum update -y \
  && yum install -y amazon-linux-extras \
  && amazon-linux-extras enable php7.4 \
  && yum install -y php php-fpm \
  default-mysql-client \
  zlib-devel \
  libzip-devel \
  unzip \
  libpng-devel \
  libjpeg-devel \
  freetype-devel \
  libwebp-devel \
  libxpm-devel \
  zip \
  libxml2-devel \
  && yum clean metadata \
  && yum install -y composer \
  && yum clean all

# GDの拡張モジュールの設定とインストール
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp --with-xpm \
  && docker-php-ext-install gd

# その他のPHP拡張モジュールのインストール
RUN docker-php-ext-install pdo_mysql mbstring dom

# Composerのインストール
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
  && composer self-update --2

# 作業ディレクトリの指定
WORKDIR /var/www
