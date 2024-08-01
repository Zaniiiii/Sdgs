<?php

namespace Database\Seeders;

use App\Models\JournalSdg;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class JournalSdgSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        foreach (range(1, 20) as $index) {
            JournalSdg::create([
                'id_journals' => $index,
                'id_sdgs' => $faker->numberBetween(1, 17)
            ]);
        }
    }
}
