<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Get a book slug
$bookRepository = app(\App\Repositories\BookRepository::class);
$book = $bookRepository->find(1);
$slug = $book['slug'] ?? 'no-slug';

echo "Book: " . $book['title'] . "\n";
echo "Slug: " . $slug . "\n";

// Generate URL from route
$url = route('books.show', $slug);
echo "Generated URL: " . $url . "\n";

// Test a direct URL
$directUrl = url("/books/{$slug}");
echo "Direct URL: " . $directUrl . "\n";

// Test if this URL will be matched by the router
$request = \Illuminate\Http\Request::create("/books/{$slug}", 'GET');
$routes = app('router')->getRoutes();
$route = $routes->match($request);

if ($route) {
    echo "Route matched: " . $route->getName() . "\n";
    echo "Controller: " . $route->getActionName() . "\n";
} else {
    echo "No route matched!\n";
}