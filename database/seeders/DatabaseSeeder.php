<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
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
