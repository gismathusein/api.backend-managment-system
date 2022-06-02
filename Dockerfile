FROM php:7.4
#FROM git:alpine
RUN docker-php-ext-install pdo pdo_mysql
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt-get update && apt-get install -y \
    git \
    zip \
    curl \
    sudo \
    unzip \
    libzip-dev \
    libicu-dev \
    libbz2-dev \
    libpng-dev \
    libjpeg-dev \
    libmcrypt-dev \
    libreadline-dev \
    libfreetype6-dev \
    g++



#COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /app
COPY . /app



RUN composer update
RUN composer install
RUN php artisan key:generate --ansi
RUN php artisan storage:link
RUN php artisan migrate --force
RUN php arisan db:seed

CMD php artisan serve --host=0.0.0.0 --port=8080
EXPOSE 8080
