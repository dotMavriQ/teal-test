<?php

// We'll use a simple method that works with any PHP installation - just copy the SVG
$sourceFile = __DIR__ . '/public/images/default-book-cover.svg';
$destinationDir = __DIR__ . '/storage/app/public/book-covers';
$jpgDestination = $destinationDir . '/default-book-cover.jpg';
$svgDestination = $destinationDir . '/default-book-cover.svg';

// Make sure the destination directory exists
if (!file_exists($destinationDir)) {
    mkdir($destinationDir, 0755, true);
}

// Copy the SVG file
copy($sourceFile, $svgDestination);
// Also copy it as JPG for systems that may expect JPG extension
copy($sourceFile, $jpgDestination);

// Also copy to public images for the default
copy($sourceFile, __DIR__ . '/public/images/default-book-cover.jpg');

echo "Default book cover setup for storage and public images.\n";