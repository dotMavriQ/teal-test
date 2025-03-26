<?php

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Generate a strong password
$password = bin2hex(random_bytes(8)); // 16 characters

try {
    // Create the users table if it doesn't exist
    if (!Schema::hasTable('users')) {
        Schema::create('users', function ($table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
        echo "Created users table.\n";
    }

    // Check if user already exists
    $user = DB::table('users')->where('email', 'dotmavriq@dotmavriq.life')->first();
    
    if ($user) {
        echo "User already exists.\n";
    } else {
        // Create the user
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'dotmavriq@dotmavriq.life',
            'password' => Hash::make($password),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        echo "User created successfully.\n";
    }
    
    echo "Email: dotmavriq@dotmavriq.life\n";
    echo "Password: $password\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}