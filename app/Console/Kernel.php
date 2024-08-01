<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */

    /** 
     *  C:\laragon\bin\cronical
     *  
     *  Cronical.exe --console --debug
     * **/
    protected function schedule(Schedule $schedule): void
    {
        $oplib_path = storage_path('app/model/scrappingOplib/main.py');
        $sinta_path = storage_path('app/model/scrappingSinta/main.py');

        // Schedule Task script 1
        $schedule->exec("python \"$oplib_path\"")
            ->everyFiveMinutes()
            ->before(function () {
                Log::info('Starting Task 1 (oplib)');
            })
            ->sendOutputTo(storage_path('logs/task1_output.log'))
            ->appendOutputTo(storage_path('logs/task1_error.log'))
            ->onSuccess(function () {
                Log::info('Task 1 (oplib) completed successfully.');
            })
            ->onFailure(function () {
                $error = file_get_contents(storage_path('logs/task1_error.log'));
                Log::error('Task 1 (oplib) failed.', ['error' => $error]);
            });

        // Schedule Task script 2
        $schedule->exec("python \"$sinta_path\"")
            ->everyFiveMinutes()
            ->before(function () {
                Log::info('Starting Task 2 (sinta)');
            })
            ->sendOutputTo(storage_path('logs/task2_output.log'))
            ->appendOutputTo(storage_path('logs/task2_error.log'))
            ->onSuccess(function () {
                Log::info('Task 2 (sinta) completed successfully.');
            })
            ->onFailure(function () {
                $error = file_get_contents(storage_path('logs/task2_error.log'));
                Log::error('Task 2 (sinta) failed.', ['error' => $error]);
            });
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
