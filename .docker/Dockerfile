FROM codemastersolutions/php:7.4.8-fpm-alpine-3.12

RUN apk update && \
    apk add --no-cache yarn git openrc && \
    yarn global add @vue/cli && \
    yarn global add nodejs npm && \
    rm -rf /var/www/html && \
    mkdir /run/openrc && \
    touch /run/openrc/softlevel

COPY ./docker-php-entrypoint /usr/local/bin/

RUN chmod +x /usr/local/bin/docker-php-entrypoint

WORKDIR /var/www

EXPOSE 5500
