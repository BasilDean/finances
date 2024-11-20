<?php

use Illuminate\Support\Facades\Artisan;


Schedule::command('refresh:currency-rates')->dailyAt('09:00');
