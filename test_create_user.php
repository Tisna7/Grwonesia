<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::create([
    'name' => 'Test Create User 5',
    'email' => 'tinkertest5@gmail.com',
    'password' => bcrypt('password'),
    'verification_code' => '999999',
    'verification_expires_at' => now()->addMinutes(15)
]);
echo "Created user OTP from object: " . $user->verification_code . "\n";
echo "Created user OTP from DB: " . \App\Models\User::find($user->id)->verification_code . "\n";
