<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ScheduleData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:schedule-data {message}';

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
        $this->call('app:controller-method', [
            'message' => $this->argument('message')
        ]);

    }
}
