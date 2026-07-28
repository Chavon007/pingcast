<?php

use Illuminate\Support\Facades\Schedule;


Schedule::command("weather:send-reports")->everyMinute();