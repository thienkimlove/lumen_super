<?php

namespace App\Console;

use App\Console\Commands\GoogleAddress;
use App\Console\Commands\InputAgent;
use App\Console\Commands\InsertInto;
use App\Console\Commands\RunManual;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        InputAgent::class,
        GoogleAddress::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('google:address')
            ->withoutOverlapping()
            ->everyMinute();
    }
}
