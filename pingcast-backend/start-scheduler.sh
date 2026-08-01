#!/bin/sh

php artisan schedule:work &
SCHEDULER_PID=$!

php -S 0.0.0.0:$PORT -t /dev/null &
SERVER_PID=$!

wait $SCHEDULER_PID $SERVER_PID