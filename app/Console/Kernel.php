<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;
use PrimeX\Packages\Core\Console\BulkAddProductsCommand;
use PrimeX\Packages\Core\Console\BulkAddProductStockCommand;
use PrimeX\Packages\Core\Console\BulkUpdateProductsCommand;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        BulkAddProductsCommand::class,
        BulkUpdateProductsCommand::class,
        BulkAddProductStockCommand::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //
    }
}
