<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use League\Csv\Reader;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\AuthorController;
use Illuminate\Http\Request;

class PublishCSV extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string     */
    protected $signature = 'app:publishCSV';

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
        $filePath = storage_path('app\data\DataDosen.csv');
        $reader = Reader::createFromPath($filePath, 'r');
        $reader->setDelimiter(';');
        $reader->setHeaderOffset(0);

        $records = $reader->getRecords();
        
        $recordsArray = iterator_to_array($records);
        $recordsArray = array_values($recordsArray);
        $json = json_encode($recordsArray);

        $controller = new AuthorController();
        $request = new Request();
        $request->replace(['data' => $json]);
        $controller->create($request);
    }
}