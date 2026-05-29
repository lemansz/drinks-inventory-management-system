<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\CategorySeeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::updateOrCreate(
            ['email' => 'admin@example.com'], // Prevents duplicates if run twice
            [
                'surname' => 'Admin',
                'first_name' => 'User',
                'password' => Hash::make('123456'),
                'email_verified_at' => now(),
            ]
        );


        // seed categories
        $this->call([CategorySeeder::class, SettingSeeder::class]);
    }
}
