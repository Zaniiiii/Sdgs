<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;


class HandlerLogCrawling extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'log:crawling {message}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $log_message = strtolower($this->argument('message'));
        if (strpos(strtolower($log_message), 'error') !== false) {
            Log::error($log_message);
        } elseif (strpos($log_message, 'warning') !== false) {
            Log::warning($log_message);
        } else {
           Log::info($log_message);
        }
    }
}
