<?php

namespace Database\Seeders;

use App\Models\AuthorJournal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class AuthorJournalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');


        foreach (range(1, 20) as $index) {
            AuthorJournal::create([
                'id_authors' => $faker->numberBetween(1, 20),
                'id_journals' => $index
            ]);
        }
    }
}
