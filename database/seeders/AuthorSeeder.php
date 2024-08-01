<?php

namespace Database\Seeders;

use App\Models\Author;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        foreach (range(1, 20) as $index) {
            Author::create([
                'name' => $faker->sentence,
                'gender' => $faker->randomElement(['L', 'P']),
                'position' => $faker->sentence,
                'highest_education' => $faker->sentence,
                'employment_status' => $faker->sentence,
                'activation_status' => $faker->randomElement(['Active', 'Inactive'])
            ]);
        }
    }
}
