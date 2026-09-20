#!/bin/sh

set -eu

composer install \
    --no-interaction \
    --no-progress \
    --prefer-dist \
    --optimize-autoloader

exec docker-php-entrypoint "$@"
