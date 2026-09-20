FROM php:8.1-fpm-alpine

WORKDIR /var/www/html

RUN docker-php-ext-install pdo pdo_mysql

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY docker/php/entrypoint.sh /usr/local/bin/blog-entrypoint

RUN chmod +x /usr/local/bin/blog-entrypoint

ENTRYPOINT ["blog-entrypoint"]

CMD ["php-fpm"]
