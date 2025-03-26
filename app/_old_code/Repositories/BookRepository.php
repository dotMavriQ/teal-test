<?php

namespace App\Repositories;

use App\Models\Book;

class BookRepository extends FileRepository
{
    public function __construct()
    {
        parent::__construct('books');
    }
    
    public function findByIsbn($isbn)
    {
        $bookData = $this->findBy('isbn', $isbn);
        
        if ($bookData) {
            return new Book($bookData);
        }
        
        return null;
    }
    
    public function saveBook(array $bookData)
    {
        // Check if book already exists by ISBN if available
        if (!empty($bookData['isbn'])) {
            $existingBook = $this->findByIsbn($bookData['isbn']);
            if ($existingBook) {
                $bookData['id'] = $existingBook->id;
            }
        }
        
        // Generate and save slug if not provided
        if (!isset($bookData['slug']) && isset($bookData['title'])) {
            $bookData['slug'] = $this->generateUniqueSlug($bookData['title'], $bookData['id'] ?? null);
        }
        
        return $this->save($bookData);
    }
    
    /**
     * Find a book by its slug
     */
    public function findBySlug($slug)
    {
        $bookData = $this->findBy('slug', $slug);
        
        if ($bookData) {
            return new Book($bookData);
        }
        
        return null;
    }
    
    /**
     * Generate a unique slug based on title
     */
    public function generateUniqueSlug($title, $id = null)
    {
        // Convert the title to a slug
        $slug = preg_replace('/[^a-z0-9]+/', '-', strtolower($title));
        $slug = trim($slug, '-');
        
        // If the slug is empty (rare case), use 'book'
        if (empty($slug)) {
            $slug = 'book';
        }
        
        // Add a suffix if this slug already exists
        $originalSlug = $slug;
        $counter = 1;
        $isUnique = false;
        
        while (!$isUnique) {
            $books = $this->all();
            $exists = false;
            
            foreach ($books as $book) {
                if (isset($book['slug']) && $book['slug'] === $slug && (!$id || $book['id'] != $id)) {
                    $exists = true;
                    break;
                }
            }
            
            if ($exists) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            } else {
                $isUnique = true;
            }
        }
        
        return $slug;
    }
    
    public function getAllBooks()
    {
        $booksData = $this->all();
        $books = [];
        
        foreach ($booksData as $bookData) {
            $books[] = new Book($bookData);
        }
        
        return $books;
    }
    
    public function importFromGoodreads(array $goodreadsData)
    {
        $importedCount = 0;
        
        foreach ($goodreadsData as $entry) {
            // Map Goodreads data to our Book model
            $title = $entry['title'] ?? 'Unknown Title';
            
            $bookData = [
                'title' => $title,
                'author' => $entry['author'] ?? 'Unknown Author',
                'description' => null,
                'isbn' => $entry['isbn'] ?? null,
                'isbn13' => $entry['isbn13'] ?? null,
                'asin' => $entry['asin'] ?? null,
                'numPages' => $entry['num_pages'] ?? null,
                'coverImage' => 'book_stock.png', // Default cover
                'publicationDate' => $this->formatDate($entry['pub_date']),
                'format' => $entry['format'] ?? null,
                'userRating' => $entry['user_rating'] ?? null,
                'avgRating' => $entry['avg_rating'] ?? null,
                'dateAdded' => $this->formatDate($entry['date_added']),
                'dateStarted' => $this->formatDate($entry['date_started']),
                'dateRead' => $this->formatDate($entry['date_read']),
                'owned' => $entry['owned'] === 'owned',
                'language' => $entry['language'] ?? '',
                'slug' => $this->generateUniqueSlug($title),
            ];
            
            $this->saveBook($bookData);
            $importedCount++;
        }
        
        return $importedCount;
    }
    
    private function formatDate($dateString)
    {
        if (empty($dateString)) {
            return null;
        }
        
        // Try different date formats
        $formats = [
            'Y-m-d',
            'M d, Y',
            'M Y',
            'Y-m',
        ];
        
        foreach ($formats as $format) {
            $date = \DateTime::createFromFormat($format, $dateString);
            if ($date) {
                return $date->format('Y-m-d');
            }
        }
        
        // If no format matches, return as is
        return $dateString;
    }
}