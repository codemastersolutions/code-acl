FROM codemastersolutions/php:7.4.8-fpm-alpine-3.12

RUN usermod -u 1000 www-data

COPY ./docker-php-entrypoint /usr/local/bin/

RUN chmod +x /usr/local/bin/docker-php-entrypoint

WORKDIR /var/www/html

EXPOSE 5500
