<?php

namespace App\Console\Commands;

use App\Imports\DataCrawller;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Models\Author;
use App\Models\Journal;
use App\Models\Sdg;

class Crawller extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:crawller {file}';
    // protected $signature = 'app:crawller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crawlling data from json file and store it to database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $file = $this->argument('file');

        // check if file contains oplib then file path contain folder preprocessOplib
        if (strpos($file, 'oplib') !== false) {
            $file_path = storage_path('result') . '\\' . 'preprocessOplib' . '\\' . $file;
        } else {
            $file_path = storage_path('result') . '\\' . 'preprocessSinta' . '\\' . $file;
        }

        Log::info('Scanning file', ['filepath' => $file_path]);
        if (!file_exists($file_path)) {
            Log::error('File not found', ['filepath' => $file_path]);
            return;
        }

        $json = json_decode(file_get_contents($file_path));

        Log::info('json', ["json"=>$json ]);

        if (empty($json)) {
            Log::error('Json file is empty', ['filepath' => $file_path]);
            return;
        }
        $author_not_found = 0;
        $abstract_not_found = 0;
        $title_not_found = 0;
        $duplicate_journal = 0;
        $success = 0;
        $scores_invalid = 0;
        $failed_fetch = 0;
        $sdg_invalid = 0;
        
        foreach ($json as $key => $value) {
            $title = $this->cleanText(strip_tags($value->judul[0] ?? $value->Judul));
            $abstract = $this->cleanText(strip_tags($value->abstrak[0] ?? $value->Abstrak));
            $authorNames = $this->cleanText(strip_tags($value->penulis ?? $value->Penulis));
            $authors = $this->getAuthorsFromString($authorNames);
            Log::info('Result', ['key' => $key, 'title' => $title]);
            $author = $this->findFirstAuthor($authors);
            if (!$author || !$abstract || !$title) {
                if (!$author) {
                    Log::info('Author not found', ['key' => $key, 'authors' => $authorNames]);
                    $author_not_found++;
                }
                if (!$abstract) {
                    Log::info('Abstract not found', ['key' => $key, 'title' => $title]);
                    $abstract_not_found++;
                }
                if (!$title) {
                    Log::info('Title not found', ['key' => $key]);
                    $title_not_found++;
                }
                continue;
            }
            
            $existingJournal = Journal::where('title', $title)
                ->whereHas('authors', function ($query) use ($author) {
                    $query->where('authors.id', $author->id);
                })
                ->first();

            if ($existingJournal) {
                Log::info('Duplicate journal found', ['key' => $key, 'title' => $title]);
                $duplicate_journal++;
                continue;
            }

            $sdgs = $this->check_sdgs($value, $abstract); // return array [sdgs1,sdgs2 dll]
            
            DB::transaction(function () use ($key,$title, $abstract, $author, $sdgs ) {
                Log::info('Storing data to database', ['key' => $key, 'title' => $title, 'author' => $author->name]);
                $journal = Journal::create([
                    'title' => $title,
                    'abstract' => $abstract
                    // 'lecturer' => implode(', ', $lecturers)
                ]);
                $journal->authors()->attach($author->id);
                if ($sdgs) {
                    foreach ($sdgs  as $data) {
                        $sdg = Sdg::where('code', $data)->first();
                        if ($sdg) {
                            $journal->sdgs()->attach($sdg->id);
                        }else{
                            Log::info('SDG not found', [ 'key' => $key, 'sdg' => $data ]);
                        }
                    }
                }
            });
            Log::info('Successfully processed entry', ['key' => $key, 'title' => $title]);
            $success++;

        }
        Log::info('Finish to store data to database', [
            'author_not_found' => $author_not_found,
            'abstract_not_found' => $abstract_not_found,
            'title_not_found' => $title_not_found,
            'duplicate_journal' => $duplicate_journal,
            'scores_invalid' => $scores_invalid,
            'sdg_invalid' => $sdg_invalid,
            'failed_fetch' => $failed_fetch,
            'total' => count($json),
        ]);
    }

    function waitAndRetry($abstract, $interval){
        $response = $this->get_response($abstract, $interval);
        if ($response->status() == 503) {
            sleep($interval);
            return  $this->waitAndRetry($abstract, $interval);
        } else {
            return $response;
        }
    }

    private function get_response($abstract, $interval){
        // cek kondisi untuk file oplib dan sinta (belum)
        return Http::withHeaders([
            'Authorization' => 'Bearer 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'
        ])->timeout($interval)
          ->post('https://api-inference.huggingface.co/models/Zaniiiii/sdgs', [
            'inputs' => substr($abstract, 0, 512)
        ]);
    }
    

    private function check_scores($response, $scores_threshold){
        try{
            if (!isset($response[0])) {
                return [];
            }
            $scores = $response[0];
            $scores = array_filter($scores, function($score) use ($scores_threshold){
                return $score['score'] >= $scores_threshold;
            });
            return $scores;
        }catch(\Exception $e){
            dump($scores);
            return [];
        }
        
    }

    private function cleanText($text)
    {
        $unwantedChars = array_merge(
            array_map('chr', range(0, 31)),  // ASCII 0-31
            array_map('chr', range(127, 159)), // ASCII 127-159
            ["\u{A0}"] // Unicode Non-Breaking Space
        );

        $text = str_replace($unwantedChars, '', $text);
        $text = trim($text);

        return $text;
    }

    private function getAuthorsFromString($authorsString)
    {
        $authors = array_map('trim', explode(',', $authorsString));
        return $authors;
    }

    private function checkAuthorExists($authorName)
    {
        return Author::where('name', $authorName)->first();
    }

    private function findFirstAuthor($authors)
    {
        foreach ($authors as $authorName) {
            $author = $this->checkAuthorExists($authorName);
            if ($author) {
                return $author;
            }
        }
        return null;
    }

    function check_sdgs($value,$abstract) {
        if (!isset($value->sdgs)) {
            $response = $this->get_response($abstract, 30);
            if ($response->status() == 503) {
                Log::info('Rate limited, waiting and retrying', ['response' => $response->json()]);
                $response = $this->waitAndRetry($abstract, 30);
            }   

            if ($response->status() != 200) {
                Log::error('Error fetching API response', ['status' => $response->status(), 'response' => $response->json()]);
                return;
            }

            $scores = $this->check_scores($response->json(), $scores_threshold = 0.5);
            if (empty($scores)) {
                Log::info('No valid scores found', ['scores' => $scores]);
                return;
            }
            
            $labels = [];
            foreach ($scores as $score) {
                $labels[] =  $score['label'];
            }

            return $labels;
        } else {
            return $value->sdgs;
        }
    }
}
