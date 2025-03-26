<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Get BookRepository to access books and saving functionality
$bookRepository = app(\App\Repositories\BookRepository::class);

// Test slug lookup
$testSlug = 'the-tao-of-pooh'; // This should match one of the books

$book = $bookRepository->findBySlug($testSlug);

if ($book) {
    echo "Success! Found book by slug: {$testSlug}\n";
    echo "Title: {$book->title}\n";
    echo "Author: {$book->author}\n";
    echo "ID: {$book->id}\n";
} else {
    echo "Failed to find book with slug: {$testSlug}\n";
    
    // Debug - search all books for this slug
    $allBooks = $bookRepository->all();
    echo "\nSearch for slug '{$testSlug}' in all books:\n";
    
    foreach ($allBooks as $bookData) {
        $bookSlug = $bookData['slug'] ?? 'no-slug';
        if ($bookSlug === $testSlug) {
            echo "MATCH FOUND: Book ID {$bookData['id']} - {$bookData['title']}\n";
            echo "Full book data: " . json_encode($bookData, JSON_PRETTY_PRINT) . "\n";
        }
    }
}