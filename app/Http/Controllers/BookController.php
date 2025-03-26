<?php

namespace App\Http\Controllers;

use App\Repositories\BookRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    protected $bookRepository;
    
    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }
    
    public function index()
    {
        $books = $this->bookRepository->getAllBooks();
        
        return view('books.index', compact('books'));
    }
    
    /**
     * Show book details using ID
     */
    public function show($id)
    {
        $book = $this->bookRepository->find($id);
        
        if (!$book) {
            return redirect()->route('books.index')->with('error', 'Book not found.');
        }
        
        return view('books.show', compact('book'));
    }
    
    /**
     * Show book details using slug
     */
    public function showBySlug($slug)
    {
        // For debugging
        if ($slug === '__debug') {
            $allBooks = $this->bookRepository->all();
            $bookSlugs = [];
            foreach ($allBooks as $bookData) {
                $bookSlugs[$bookData['id']] = [
                    'title' => $bookData['title'],
                    'slug' => $bookData['slug'] ?? 'no-slug'
                ];
            }
            return response()->json($bookSlugs);
        }
        
        // Direct debug log
        error_log("BookController::showBySlug - Searching for book with slug: {$slug}");
        
        // If slug lookup fails, we'll try to find books with similar slugs for debugging
        $book = $this->bookRepository->findBySlug($slug);
        
        if (!$book) {
            error_log("BookController::showBySlug - Book not found with slug: {$slug}");
            
            // Look for similar slugs
            $allBooks = $this->bookRepository->all();
            $similarSlugs = [];
            
            foreach ($allBooks as $bookData) {
                if (isset($bookData['slug'])) {
                    if (strpos($bookData['slug'], $slug) !== false || strpos($slug, $bookData['slug']) !== false) {
                        $similarSlugs[] = [
                            'id' => $bookData['id'],
                            'title' => $bookData['title'],
                            'slug' => $bookData['slug']
                        ];
                    }
                }
            }
            
            if (!empty($similarSlugs)) {
                error_log("BookController::showBySlug - Found similar slugs: " . json_encode($similarSlugs));
            }
            
            // Try to redirect to the closest match if any
            if (!empty($similarSlugs)) {
                $closestMatch = $similarSlugs[0];
                error_log("BookController::showBySlug - Redirecting to closest match: " . $closestMatch['slug']);
                return redirect()->route('books.show', $closestMatch['slug'])
                    ->with('info', "Book with slug '{$slug}' not found. Redirected to closest match.");
            }
            
            return redirect()->route('books.index')->with('error', "Book not found with slug: {$slug}");
        }
        
        error_log("BookController::showBySlug - Found book: {$book->title} (ID: {$book->id})");
        
        return view('books.show', compact('book'));
    }
    
    public function create()
    {
        return view('books.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'isbn' => 'nullable|string|max:20',
            'publication_date' => 'nullable|date',
            'format' => 'nullable|string|max:50',
            'language' => 'nullable|string|max:50',
            'cover_image' => 'nullable|image|max:2048',
        ]);
        
        $bookData = [
            'title' => $validated['title'],
            'author' => $validated['author'],
            'description' => $validated['description'],
            'isbn' => $validated['isbn'],
            'publicationDate' => $validated['publication_date'],
            'format' => $validated['format'],
            'language' => $validated['language'],
            'coverImage' => 'book_stock.png',
        ];
        
        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            $coverImage = $request->file('cover_image');
            $filename = time() . '_' . $coverImage->getClientOriginalName();
            
            // Ensure the storage directory exists
            if (!Storage::exists('public/book-covers')) {
                Storage::makeDirectory('public/book-covers');
            }
            
            $coverImage->storeAs('public/book-covers', $filename);
            $bookData['coverImage'] = $filename;
        }
        
        $book = $this->bookRepository->saveBook($bookData);
        
        return redirect()->route('books.show', $book['id'])->with('success', 'Book added successfully.');
    }
    
    public function edit($id)
    {
        $bookData = $this->bookRepository->find($id);
        
        if (!$bookData) {
            return redirect()->route('books.index')->with('error', 'Book not found.');
        }
        
        // Convert the array to a Book object
        $book = new \App\Models\Book($bookData);
        
        return view('books.edit', compact('book'));
    }
    
    public function update(Request $request, $id)
    {
        $bookData = $this->bookRepository->find($id);
        
        if (!$bookData) {
            return redirect()->route('books.index')->with('error', 'Book not found.');
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'isbn' => 'nullable|string|max:20',
            'publication_date' => 'nullable|date',
            'format' => 'nullable|string|max:50',
            'language' => 'nullable|string|max:50',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'user_rating' => 'nullable|integer|min:0|max:100',
        ]);
        
        $updatedBookData = [
            'id' => $id,
            'title' => $validated['title'],
            'author' => $validated['author'],
            'description' => $validated['description'],
            'isbn' => $validated['isbn'],
            'publicationDate' => $validated['publication_date'],
            'format' => $validated['format'],
            'language' => $validated['language'],
            'userRating' => $validated['user_rating'],
            'coverImage' => $bookData['coverImage'] ?? 'default-book-cover.jpg',
        ];
        
        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            $coverImage = $request->file('cover_image');
            
            // Create a more readable filename with timestamp and sanitized book title
            $safeTitle = preg_replace('/[^a-z0-9]+/', '-', strtolower($updatedBookData['title']));
            $filename = time() . '_' . $safeTitle . '.' . $coverImage->getClientOriginalExtension();
            
            // Ensure the storage directory exists
            if (!Storage::exists('public/book-covers')) {
                Storage::makeDirectory('public/book-covers');
            }
            
            // Delete old cover image if it's not the default
            if ($bookData['coverImage'] && $bookData['coverImage'] !== 'default-book-cover.jpg' && $bookData['coverImage'] !== 'book_stock.png') {
                Storage::delete('public/book-covers/' . $bookData['coverImage']);
            }
            
            // Store the image
            $coverImage->storeAs('public/book-covers', $filename);
            $updatedBookData['coverImage'] = $filename;
            
            // Add success message about the image
            session()->flash('image_success', 'Book cover image was successfully updated.');
        }
        
        $this->bookRepository->saveBook($updatedBookData);
        
        return redirect()->route('books.show', $id)->with('success', 'Book updated successfully.');
    }
    
    public function destroy($id)
    {
        $book = $this->bookRepository->find($id);
        
        if (!$book) {
            return redirect()->route('books.index')->with('error', 'Book not found.');
        }
        
        // Delete cover image if it's not the default
        if ($book['coverImage'] && $book['coverImage'] !== 'default-book-cover.jpg') {
            Storage::delete('public/book-covers/' . $book['coverImage']);
        }
        
        $this->bookRepository->delete($id);
        
        return redirect()->route('books.index')->with('success', 'Book deleted successfully.');
    }
    
    public function importForm()
    {
        return view('books.import');
    }
    
    public function import(Request $request)
    {
        $request->validate([
            'goodreads_file' => 'required|file|mimes:json',
        ]);
        
        $file = $request->file('goodreads_file');
        $goodreadsData = json_decode(file_get_contents($file->getPathname()), true);
        
        if (!is_array($goodreadsData)) {
            return redirect()->back()->with('error', 'Invalid JSON format in the file.');
        }
        
        $importedCount = $this->bookRepository->importFromGoodreads($goodreadsData);
        
        return redirect()->route('books.index')->with('success', "{$importedCount} books imported successfully.");
    }
}