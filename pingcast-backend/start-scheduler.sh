#!/bin/sh

php artisan schedule:work &

php -S 0.0.0.0:$PORT -t public