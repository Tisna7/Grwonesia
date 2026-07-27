<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            DemoBusinessSeeder::class,
            RegionalDemoSeeder::class,
            AdminDemoSeeder::class,
        ]);
        User::firstOrCreate(
            ['email' => 'budi@grownesia.id'],
            [
                'name' => 'Budi Santoso',
                'role' => 'user',
                'password' => Hash::make('password'),
            ]
        );
    }
}
