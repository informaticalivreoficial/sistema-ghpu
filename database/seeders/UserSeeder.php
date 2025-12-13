<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => env('ADMIN_NOME'),
                'company_id' => Company::inRandomOrder()->first()?->id ?? Company::factory(),
                'email' => env('ADMIN_EMAIL'),
                'email_verified_at' => now(),
                'password' => bcrypt(env('ADMIN_PASS')),
                'code' => env('ADMIN_PASS'),
                'remember_token' => \Illuminate\Support\Str::random(10),                
                'created_at' => now(),          
                'status' => 1
            ]            
        ]);

        User::factory()->count(20)->create();
    }
}
