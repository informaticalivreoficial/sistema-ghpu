<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CompanySeeder::class,
            ConfigTableSeeder::class,
            UserSeeder::class,            
            CatPostSeeder::class,            
            OcorrenciaSeeder::class,
            TemplateTableSeeder::class,   
        ]);
    }
}
