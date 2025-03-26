<?php

namespace App\Models;

class Book
{
    public $id;
    public $title;
    public $author;
    public $description;
    public $isbn;
    public $isbn13;
    public $asin;
    public $numPages;
    public $coverImage;
    public $publicationDate;
    public $format;
    public $userRating;
    public $avgRating;
    public $dateAdded;
    public $dateStarted;
    public $dateRead;
    public $owned;
    public $language;
    public $createdAt;
    public $updatedAt;
    public $slug;
    
    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
    }
    
    public function fill(array $attributes): self
    {
        foreach ($attributes as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
        
        return $this;
    }
    
    public function getRatingPercentage()
    {
        if (!$this->userRating) {
            return 0;
        }
        
        return $this->userRating;
    }
    
    public function getFormattedRating()
    {
        if (!$this->userRating) {
            return 'Not rated';
        }
        
        $stars = round($this->userRating / 20); // Convert percentage to 5-star scale
        return str_repeat('★', $stars) . str_repeat('☆', 5 - $stars);
    }
    
    public function getCoverImageUrl()
    {
        if (!$this->coverImage || $this->coverImage === 'default-book-cover.jpg') {
            return asset('images/book_stock.png');
        }
        
        return asset('storage/book-covers/' . $this->coverImage);
    }
    
    public function getReadStatus()
    {
        if ($this->dateRead) {
            return 'Read';
        }
        
        if ($this->dateStarted) {
            return 'Reading';
        }
        
        return 'Unread';
    }
    
    /**
     * Get the URL-friendly slug for this book
     */
    public function getSlug()
    {
        // If we have a slug property, use it
        if (!empty($this->slug)) {
            return $this->slug;
        }
        
        // Fallback to a simple slug if none is set
        $title = $this->title ?? 'book';
        
        // Remove special characters and convert to lowercase
        $slug = preg_replace('/[^a-z0-9]+/', '-', strtolower($title));
        $slug = trim($slug, '-');
        
        // If the slug is empty, use 'book'
        if (empty($slug)) {
            $slug = 'book';
        }
        
        return $slug;
    }
    
}