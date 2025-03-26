<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MigrateFileDataTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_migrates_books_from_json_file_to_database()
    {
        // Create a test books.json file
        $booksData = [
            [
                'id' => 1,
                'title' => 'Test Book 1',
                'author' => 'Test Author 1',
                'isbn' => '1234567890',
                'coverImage' => 'book_stock.png',
                'slug' => 'test-book-1',
                'created_at' => now()->toDateTimeString(),
                'updated_at' => now()->toDateTimeString(),
            ],
            [
                'id' => 2,
                'title' => 'Test Book 2',
                'author' => 'Test Author 2',
                'isbn' => '0987654321',
                'coverImage' => 'book_stock.png',
                'slug' => 'test-book-2',
                'created_at' => now()->toDateTimeString(),
                'updated_at' => now()->toDateTimeString(),
            ],
        ];
        
        Storage::disk('local')->put('books.json', json_encode($booksData));
        
        // Run the migration command
        Artisan::call('migrate:file-data');
        
        // Check if books are migrated to the database
        $this->assertDatabaseHas('books', [
            'title' => 'Test Book 1',
            'author' => 'Test Author 1',
            'isbn' => '1234567890',
        ]);
        
        $this->assertDatabaseHas('books', [
            'title' => 'Test Book 2',
            'author' => 'Test Author 2',
            'isbn' => '0987654321',
        ]);
        
        // Clean up test file
        Storage::disk('local')->delete('books.json');
    }

    /** @test */
    public function it_migrates_users_from_json_file_to_database()
    {
        // Create a test users.json file
        $usersData = [
            [
                'id' => 1,
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => bcrypt('password'),
                'created_at' => now()->toDateTimeString(),
                'updated_at' => now()->toDateTimeString(),
            ],
        ];
        
        Storage::disk('local')->put('users.json', json_encode($usersData));
        
        // Run the migration command
        Artisan::call('migrate:file-data');
        
        // Check if users are migrated to the database
        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        
        // Clean up test file
        Storage::disk('local')->delete('users.json');
    }

    /** @test */
    public function it_creates_default_admin_user_when_no_users_json_exists()
    {
        // Make sure there's no users.json file
        if (Storage::disk('local')->exists('users.json')) {
            Storage::disk('local')->delete('users.json');
        }
        
        // Run the migration command
        Artisan::call('migrate:file-data');
        
        // Check if default admin user is created
        $this->assertDatabaseHas('users', [
            'name' => 'Admin',
            'email' => 'dotmavriq@dotmavriq.life',
        ]);
    }
    
    /**
     * Tear down the test case.
     */
    public function tearDown(): void
    {
        // Clean up any files created during tests
        if (Storage::disk('local')->exists('books.json')) {
            Storage::disk('local')->delete('books.json');
        }
        
        if (Storage::disk('local')->exists('users.json')) {
            Storage::disk('local')->delete('users.json');
        }
        
        parent::tearDown();
    }
}