#!/bin/bash

set -e

if [ "$IS_LARAVEL" = "true" ]; then
    echo "Clearing cached Laravel configuration ..."

    php artisan optimize:clear

    echo "DB_HOST=${DB_HOST:-EMPTY}"
    echo "DB_DATABASE=${DB_DATABASE:-EMPTY}"

    if [ "$RAILPACK_SKIP_MIGRATIONS" != "true" ]; then
        echo "Running Laravel migrations ..."

        php artisan migrate --force
    fi

    php artisan storage:link || true

    php artisan optimize

    echo "Starting Laravel server ..."
fi

exec docker-php-entrypoint \
    --config /Caddyfile \
    --adapter caddyfile \
    2>&1