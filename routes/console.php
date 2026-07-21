<?php

use Illuminate\Support\Facades\Schedule;

// Daily low stock check at 8am
Schedule::command('inspire')->dailyAt('08:00');

// Recurring expense check - 1st of month
Schedule::command('inspire')->monthlyOn(1, '00:00');
