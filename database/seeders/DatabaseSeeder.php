<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ConfigTableSeeder::class,
            CatPostSeeder::class,
            CompanySeeder::class,
            OcorrenciaSeeder::class,
            TemplateTableSeeder::class,   
        ]);
    }
}
