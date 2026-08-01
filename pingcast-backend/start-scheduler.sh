#!/bin/sh

mkdir -p /tmp/empty

php -S 0.0.0.0:$PORT -t /tmp/empty &

while true; do
  php artisan schedule:run
  sleep 60
done