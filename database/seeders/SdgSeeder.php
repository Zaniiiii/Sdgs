<?php

namespace Database\Seeders;

use App\Models\Sdg;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class SdgSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        foreach (range(1, 17) as $index) {
            Sdg::create([
                'name' => $faker->sentence,
                'description' => $faker->paragraph,
                'code' => 'SDGS' . $index
            ]);
        }
    }
}
