<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\Inspire::class,
        \App\Console\Commands\SyncStockWithTorqus::class,
        \App\Console\Commands\DbBackup::class,
        \App\Console\Commands\OrderReport::class,
        \App\Console\Commands\MarkOrdersAsSettled::class,
        \App\Console\Commands\UpdateMembership::class,
        \App\Console\Commands\JoolehSync::class,
        \App\Console\Commands\SyncBuenoRewards::class,
        \App\Console\Commands\CreateAddressesFromOrders::class,
        \App\Console\Commands\ResizeImage::class,
        \App\Console\Commands\SyncUserCouponStatus::class,
        \App\Console\Commands\QueueLength::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('inspire')
                 ->hourly();
    }
}
