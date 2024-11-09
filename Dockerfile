FROM php:8.3-cli-alpine

RUN apk add git

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

COPY ./composer.json ./composer.lock ./

COPY ./controller ./controller
COPY ./lang ./lang
COPY ./model ./model
COPY ./view ./view
COPY ./public ./public

RUN composer install --no-dev --optimize-autoloader

EXPOSE 8000

CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
