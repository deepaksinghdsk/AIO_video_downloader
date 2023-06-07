FROM richarvey/nginx-php-fpm:1.9.1

COPY . .

# Image config
ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

# Laravel config
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr

# Allow composer to run as root
ENV COMPOSER_ALLOW_SUPERUSER 1

CMD ["/start.sh"]


#FROM php:7.2-apache

#RUN apt-get update && \
 #   apt-get install -y libpq-dev && \
  #  docker-php-ext-install pdo pdo_pgsql pdo_mysql mbstring
    
#RUN apt-get install -y libzip-dev libjpeg62-turbo-dev libpng-dev libfreetype6-dev

# Install extensions

#RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl

#RUN docker-php-ext-configure gd –with-gd –with-freetype-dir=/usr/include/ –with-jpeg-dir=/usr/include/ –with-png-dir=/usr/include/

#RUN docker-php-ext-install gd

#COPY . /var/www/html/
