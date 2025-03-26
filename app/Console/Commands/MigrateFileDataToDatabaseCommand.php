<?php

namespace App\Console\Commands;

use App\Models\Book;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MigrateFileDataToDatabaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:file-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate data from JSON files to the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting migration of file data to database...');
        
        $this->migrateUsers();
        $this->migrateBooks();
        
        $this->info('File data migration completed successfully!');
    }
    
    /**
     * Migrate users from JSON file to database.
     */
    private function migrateUsers()
    {
        $this->info('Migrating users...');
        
        $usersFilePath = storage_path('app/users.json');
        
        if (!file_exists($usersFilePath)) {
            $this->warn('Users file not found. Creating default admin user...');
            
            // Create a default admin user
            User::create([
                'name' => 'Admin',
                'email' => 'dotmavriq@dotmavriq.life',
                'password' => Hash::make('TEALAdmin@2025#Secure'),
            ]);
            
            $this->info('Default admin user created.');
            return;
        }
        
        $usersData = json_decode(file_get_contents($usersFilePath), true);
        
        $bar = $this->output->createProgressBar(count($usersData));
        $bar->start();
        
        foreach ($usersData as $userData) {
            $user = User::where('email', $userData['email'])->first();
            
            if (!$user) {
                User::create([
                    'name' => $userData['name'] ?? 'User',
                    'email' => $userData['email'],
                    'password' => $userData['password'], // Password is already hashed
                    'remember_token' => $userData['rememberToken'] ?? null,
                    'created_at' => $userData['created_at'] ?? now(),
                    'updated_at' => $userData['updated_at'] ?? now(),
                ]);
            }
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        $this->info('Users migration completed!');
    }
    
    /**
     * Migrate books from JSON file to database.
     */
    private function migrateBooks()
    {
        $this->info('Migrating books...');
        
        $booksFilePath = storage_path('app/books.json');
        
        if (!file_exists($booksFilePath)) {
            $this->warn('Books file not found. Skipping book migration.');
            return;
        }
        
        $booksData = json_decode(file_get_contents($booksFilePath), true);
        
        $bar = $this->output->createProgressBar(count($booksData));
        $bar->start();
        
        foreach ($booksData as $bookData) {
            // Check if book already exists
            $book = Book::where('slug', $bookData['slug'] ?? '')->first();
            
            if (!$book) {
                // Map old JSON structure to new database structure
                Book::create([
                    'title' => $bookData['title'] ?? 'Unknown Title',
                    'author' => $bookData['author'] ?? 'Unknown Author',
                    'description' => $bookData['description'] ?? null,
                    'isbn' => $bookData['isbn'] ?? null,
                    'isbn13' => $bookData['isbn13'] ?? null,
                    'asin' => $bookData['asin'] ?? null,
                    'num_pages' => $bookData['numPages'] ?? null,
                    'cover_image' => $bookData['coverImage'] ?? 'book_stock.png',
                    'publication_date' => $bookData['publicationDate'] ?? null,
                    'format' => $bookData['format'] ?? null,
                    'user_rating' => $bookData['userRating'] ?? null,
                    'avg_rating' => $bookData['avgRating'] ?? null,
                    'date_added' => $bookData['dateAdded'] ?? null,
                    'date_started' => $bookData['dateStarted'] ?? null,
                    'date_read' => $bookData['dateRead'] ?? null,
                    'owned' => $bookData['owned'] ?? false,
                    'language' => $bookData['language'] ?? null,
                    'slug' => $bookData['slug'] ?? Str::slug($bookData['title'] ?? 'unknown'),
                    'created_at' => $bookData['created_at'] ?? now(),
                    'updated_at' => $bookData['updated_at'] ?? now(),
                ]);
            }
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        $this->info('Books migration completed!');
    }
}