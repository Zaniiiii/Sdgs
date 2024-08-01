<?php

namespace Database\Seeders;

use App\Models\Journal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class JournalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        
        foreach (range(1, 20) as $index) {
            Journal::create([
                'title' => $faker->sentence,
                'abstract' => $faker->paragraph
            ]);
        }
    }
}
