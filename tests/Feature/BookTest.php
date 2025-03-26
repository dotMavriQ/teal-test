<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BookTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a test user
        $this->user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);
        
        // Create test storage directory
        Storage::fake('public');
    }

    /** @test */
    public function user_can_view_books_list()
    {
        // Create some books
        Book::factory()->count(3)->create();
        
        $response = $this->actingAs($this->user)
                        ->get(route('books.index'));
        
        $response->assertStatus(200);
        $response->assertViewIs('books.index');
        $response->assertViewHas('books');
    }

    /** @test */
    public function user_can_create_a_book()
    {
        $bookData = [
            'title' => 'Test Book',
            'author' => 'Test Author',
            'description' => 'Test Description',
            'isbn' => '1234567890',
            'publication_date' => '2023-01-01',
            'format' => 'Hardcover',
            'language' => 'English',
        ];
        
        $response = $this->actingAs($this->user)
                        ->post(route('books.store'), $bookData);
        
        $response->assertRedirect();
        
        $this->assertDatabaseHas('books', [
            'title' => 'Test Book',
            'author' => 'Test Author',
        ]);
    }

    /** @test */
    public function user_can_view_a_book()
    {
        $book = Book::factory()->create();
        
        $response = $this->actingAs($this->user)
                        ->get(route('books.show', $book));
        
        $response->assertStatus(200);
        $response->assertViewIs('books.show');
        $response->assertViewHas('book');
    }

    /** @test */
    public function user_can_update_a_book()
    {
        $book = Book::factory()->create();
        
        $updatedData = [
            'title' => 'Updated Title',
            'author' => 'Updated Author',
            'description' => 'Updated Description',
            'isbn' => $book->isbn,
            'publication_date' => $book->publication_date,
            'format' => $book->format,
            'language' => $book->language,
            'user_rating' => 80,
        ];
        
        $response = $this->actingAs($this->user)
                        ->put(route('books.update', $book), $updatedData);
        
        $response->assertRedirect();
        
        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'title' => 'Updated Title',
            'author' => 'Updated Author',
        ]);
    }

    /** @test */
    public function user_can_delete_a_book()
    {
        $book = Book::factory()->create();
        
        $response = $this->actingAs($this->user)
                        ->delete(route('books.destroy', $book));
        
        $response->assertRedirect(route('books.index'));
        
        $this->assertDatabaseMissing('books', [
            'id' => $book->id,
        ]);
    }

    /** @test */
    public function slug_is_generated_automatically()
    {
        $book = Book::create([
            'title' => 'Test Book With Spaces',
            'author' => 'Test Author',
        ]);
        
        $this->assertEquals('test-book-with-spaces', $book->slug);
    }

    /** @test */
    public function slugs_are_unique()
    {
        // Create a book with a title that will generate a specific slug
        $book1 = Book::create([
            'title' => 'Test Book',
            'author' => 'Author One',
        ]);
        
        // Create another book with the same title
        $book2 = Book::create([
            'title' => 'Test Book',
            'author' => 'Author Two',
        ]);
        
        // The second book should have a different slug
        $this->assertNotEquals($book1->slug, $book2->slug);
    }
}