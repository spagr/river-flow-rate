FROM php:7.4.23-fpm

RUN \
 apt-get update && \
 apt-get install -y vim git curl zip unzip htop mc && \
# Install PHP extensions
# pdo_mysql
 docker-php-ext-install pdo_mysql && \
# opcache
 docker-php-ext-install opcache && \
# mbstring
 docker-php-ext-install mbstring && \
# bcmath
 docker-php-ext-install bcmath && \
# exif
 docker-php-ext-install exif && \
# zip
 apt-get install -y libzip-dev && \
 docker-php-ext-configure zip --with-libzip && \
 docker-php-ext-install zip && \
# xdebug
 pecl install xdebug && \
 docker-php-ext-enable xdebug && \
# gd
 apt-get install -y \
  libpng-dev \
  libjpeg-dev \
  libfreetype6-dev \
  libpng-dev && \
 docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ --with-png-dir=/usr/include/ && \
 docker-php-ext-install -j$(getconf _NPROCESSORS_ONLN) gd && \
 # sockets
 docker-php-ext-install sockets && \
 # AMQP
 apt-get install -y \
    librabbitmq-dev \
    libssh-dev && \
 pecl install amqp && \
 docker-php-ext-enable amqp && \
 # PCNTL
 docker-php-ext-install pcntl && \
 #
# cleanup
 rm -rf \
    /tmp/* \
    /var/lib/apt/lists/* \
    /var/tmp/*

# Set UID & GID for php-fpm user to 1000:1000
RUN \
 usermod -u 1000 www-data && \
 groupmod -g 1000 www-data && \
# enable login for www-data user
 usermod -d /home/www-data -s /bin/bash www-data

# Install composer system-wide
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    chmod +x /usr/local/bin/composer && \
    chown www-data:www-data /usr/local/bin/composer

# Setup environment
ENV DEV=1
RUN mkdir -p /app

WORKDIR /app
