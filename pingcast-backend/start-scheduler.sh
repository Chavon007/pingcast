#!/bin/sh

mkdir -p /tmp/empty

php artisan schedule:work &
SCHEDULER_PID=$!

php -S 0.0.0.0:$PORT -t /tmp/empty &
SERVER_PID=$!

wait $SCHEDULER_PID $SERVER_PID