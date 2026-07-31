#!/bin/sh
php artisan schedule:work &
php -S 0.0.0.0:10000 -t /dev/null