<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Imamgg',
            'email' => 'imamgg@gmail.com',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'Rizal',
            'email' => 'rizal@gmail.com',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'Sonia',
            'email' => 'sonia@gmail.com',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'Elvita',
            'email' => 'elvita@gmail.com',
            'password' => bcrypt('password'),
        ]);
    }
}
