<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Load the JSON data
$goodreadsData = json_decode(file_get_contents(__DIR__ . '/goodreads_data.json'), true);

if (!is_array($goodreadsData)) {
    die("Error: Unable to parse the Goodreads data JSON file.\n");
}

// Import books using the BookRepository
$bookRepository = app(\App\Repositories\BookRepository::class);
$importedCount = $bookRepository->importFromGoodreads($goodreadsData);

echo "Successfully imported {$importedCount} books from Goodreads data.\n";