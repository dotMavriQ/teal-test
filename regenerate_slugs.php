<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Get BookRepository to access books and saving functionality
$bookRepository = app(\App\Repositories\BookRepository::class);

// Get all books
$books = $bookRepository->all();
$updatedCount = 0;

// Go through each book and add a slug if it doesn't have one
foreach ($books as $book) {
    if (empty($book['slug'])) {
        $title = $book['title'] ?? 'Unknown Title';
        $book['slug'] = $bookRepository->generateUniqueSlug($title, $book['id'] ?? null);
        $bookRepository->saveBook($book);
        $updatedCount++;
        echo "Added slug for book: " . $title . " -> " . $book['slug'] . "\n";
    }
}

echo "Regenerated slugs for {$updatedCount} books.\n";