<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Get BookRepository to access books and saving functionality
$bookRepository = app(\App\Repositories\BookRepository::class);

// Get book by ID for testing
$id = isset($argv[1]) ? (int)$argv[1] : 1;
$bookData = $bookRepository->find($id);

echo "Book ID: {$id}\n";
echo "Title: " . ($bookData['title'] ?? 'N/A') . "\n";
echo "Slug: " . ($bookData['slug'] ?? 'N/A') . "\n";

// Test findBySlug
if (isset($bookData['slug'])) {
    $bySlug = $bookRepository->findBySlug($bookData['slug']);
    if ($bySlug) {
        echo "Found by slug: YES (ID: {$bySlug->id})\n";
    } else {
        echo "Found by slug: NO\n";
        
        // Debug all books with their slugs
        $allBooks = $bookRepository->all();
        echo "\nAll book slugs:\n";
        foreach ($allBooks as $book) {
            if (isset($book['slug']) && $book['slug'] === $bookData['slug']) {
                echo "- [{$book['id']}] {$book['title']} -> {$book['slug']} (MATCH)\n";
            } else if (isset($book['slug'])) {
                echo "- [{$book['id']}] {$book['title']} -> {$book['slug']}\n";
            } else {
                echo "- [{$book['id']}] {$book['title']} -> NO SLUG\n";
            }
        }
    }
}