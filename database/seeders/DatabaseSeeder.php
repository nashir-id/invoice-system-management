<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Urutan penting! User dulu, baru klien
        $this->call([
            UserSeeder::class,
            // ClientSeeder::class,
        ]);
    }
}