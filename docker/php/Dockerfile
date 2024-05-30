# FROM php:7.4.9-fpm

# RUN mkdir -p /usr/local/etc/php/

# COPY php.ini /usr/local/etc/php/
# COPY php-fpm.conf /usr/local/etc/php-fpm.conf



# RUN apt-get update && apt-get install -y default-mysql-client
# RUN apt-get install -y \ 
#   zlib1g-dev \
#   libzip-dev \
#   unzip libpng-dev \
#   libjpeg-dev \
#   libfreetype6-dev \
#   libwebp-dev \
#   libxpm-dev \
#   zip \
#   libxml2-dev

# RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp --with-xpm
# RUN docker-php-ext-install gd 
# RUN docker-php-ext-install pdo_mysql
# RUN zip
# RUN docker-php-ext-install mbstring 
# RUN docker-php-ext-install dom

# RUN curl -sS https://getcomposer.org/installer | php \
#   && mv composer.phar /usr/local/bin/composer \
#   && composer self-update

# WORKDIR /var/www

# FROM php:7.4.9-fpm

# COPY php.ini /usr/local/etc/php/

# RUN apt update \
#   && apt install -y default-mysql-client zlib1g-dev libzip-dev unzip \
#   && docker-php-ext-install pdo_mysql zip

# RUN curl -sS https://getcomposer.org/installer | php \
#   && mv composer.phar /usr/local/bin/composer \
#   && composer self-update

# WORKDIR /var/www

FROM php:7.4.9-fpm

COPY php.ini /usr/local/etc/php/

RUN apt update \
  && apt install -y default-mysql-client zlib1g-dev libzip-dev unzip libpng-dev libjpeg-dev libfreetype6-dev libwebp-dev libxpm-dev \
  && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp --with-xpm \
  && docker-php-ext-install gd pdo_mysql zip mbstring dom

RUN curl -sS https://getcomposer.org/installer | php \
  && mv composer.phar /usr/local/bin/composer \
  && composer self-update

WORKDIR /var/www

