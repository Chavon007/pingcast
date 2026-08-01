#!/bin/sh

mkdir -p /tmp/empty

php -S 0.0.0.0:$PORT -t /tmp/empty &

exec php artisan schedule:work