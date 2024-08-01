<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(JournalSeeder::class);
        $this->call(SdgSeeder::class);
        // $this->call(AuthorSeeder::class);
        $this->call(AuthorJournalSeeder::class);
        $this->call(JournalSdgSeeder::class);
    }
}
