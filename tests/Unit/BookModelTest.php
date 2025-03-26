<?php

namespace Tests\Unit;

use App\Models\Book;
use PHPUnit\Framework\TestCase;

class BookModelTest extends TestCase
{
    /** @test */
    public function it_formats_rating_correctly()
    {
        // Create a book model manually without DB
        $book = new Book();
        $book->user_rating = 80;
        
        // Test the formatted rating
        $this->assertEquals('★★★★☆', $book->getFormattedRating());
    }

    /** @test */
    public function it_returns_not_rated_for_null_rating()
    {
        // Create a book model manually without DB
        $book = new Book();
        $book->user_rating = null;
        
        // Test the formatted rating for null
        $this->assertEquals('Not rated', $book->getFormattedRating());
    }

    /** @test */
    public function it_returns_read_status_correctly()
    {
        // Create a book model manually
        $book = new Book();
        
        // Test unread status
        $book->date_started = null;
        $book->date_read = null;
        $this->assertEquals('Unread', $book->getReadStatus());
        
        // Test reading status
        $book->date_started = '2023-01-01';
        $book->date_read = null;
        $this->assertEquals('Reading', $book->getReadStatus());
        
        // Test read status
        $book->date_started = '2023-01-01';
        $book->date_read = '2023-02-01';
        $this->assertEquals('Read', $book->getReadStatus());
    }
}