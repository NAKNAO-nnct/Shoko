FROM php:8.4-fpm

COPY ./docker/php/php.ini /usr/local/etc/php/php.ini

RUN apt-get update && apt-get -y install libzip-dev libpng-dev unzip supervisor ncat ca-certificates curl gnupg libpq-dev\
    && pecl install redis opentelemetry \
    && docker-php-ext-enable redis opentelemetry

RUN docker-php-ext-install zip pdo_pgsql pdo_mysql gd mysqli

COPY ./docker/php/zzz-docker.conf /usr/local/etc/php-fpm.d/zzz-docker.conf

COPY ./docker/php/supervisord.conf /etc/supervisor/supervisord.conf

# install nginx
RUN apt-get update && apt-get -y install nginx

# install opentelemetry module
# RUN apt-get update && apt-get -y install nginx-module-otel

COPY ./docker/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY ./docker/nginx/nginx.conf /etc/nginx/nginx.conf

# node install
# RUN mkdir -p /etc/apt/keyrings && curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg
# ARG NODE_MAJOR=22
# RUN echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_$NODE_MAJOR.x nodistro main" | tee /etc/apt/sources.list.d/nodesource.list
# RUN apt-get update && apt-get install -y nodejs

# Composer install
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_HOME=/composer
ENV PATH=$PATH:/composer/vendor/bin

WORKDIR /var/www

RUN composer global require "laravel/installer"

RUN chown -R root:root . && chmod -R 777 /var/www/

RUN apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# entrypoint
COPY docker/php/entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/entrypoint.sh
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# 自己証明書の作成
RUN openssl genrsa 2048 > /etc/nginx/server.key \
    && openssl req -new -key /etc/nginx/server.key -subj "/C=JP/ST=Tokyo/L=Tokyo/O=Example Ltd./OU=Web/CN=example.com" > /etc/nginx/server.csr \
    && openssl x509 -days 36500 -req -signkey /etc/nginx/server.key < /etc/nginx/server.csr > /etc/nginx/server.crt

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]
