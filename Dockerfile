FROM php:7.4-fpm-alpine

# Setup working directory
WORKDIR /var/www

RUN apk add --update --no-cache  bash alpine-sdk autoconf  vim

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
EXPOSE 9000
COPY ./entrypoint.sh /tmp/entrypoint.sh
RUN ["chmod", "+x", "/tmp/entrypoint.sh"]
COPY ./ /var/www/

RUN composer install

RUN chown -R www-data:www-data /var/www
ENTRYPOINT ["/tmp/entrypoint.sh"]
