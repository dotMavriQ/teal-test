<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::orderBy('title')->get();
        
        return view('books.index', compact('books'));
    }
    
    /**
     * Show book details
     */
    public function show(Book $book)
    {
        return view('books.show', compact('book'));
    }
    
    /**
     * Show book details using slug
     */
    public function showBySlug($slug)
    {
        // For debugging
        if ($slug === '__debug') {
            $bookSlugs = Book::select('id', 'title', 'slug')->get()
                ->map(function ($book) {
                    return [
                        'id' => $book->id,
                        'title' => $book->title,
                        'slug' => $book->slug ?? 'no-slug'
                    ];
                });
            return response()->json($bookSlugs);
        }
        
        // Direct debug log
        logger("BookController::showBySlug - Searching for book with slug: {$slug}");
        
        // If slug lookup fails, we'll try to find books with similar slugs for debugging
        $book = Book::where('slug', $slug)->first();
        
        if (!$book) {
            logger("BookController::showBySlug - Book not found with slug: {$slug}");
            
            // Look for similar slugs
            $similarSlugs = Book::where('slug', 'like', "%{$slug}%")
                ->orWhere('slug', 'like', "{$slug}%")
                ->orWhere('slug', 'like', "%{$slug}")
                ->select('id', 'title', 'slug')
                ->get()
                ->toArray();
            
            if (!empty($similarSlugs)) {
                logger("BookController::showBySlug - Found similar slugs: " . json_encode($similarSlugs));
            }
            
            // Try to redirect to the closest match if any
            if (!empty($similarSlugs)) {
                $closestMatch = $similarSlugs[0];
                logger("BookController::showBySlug - Redirecting to closest match: " . $closestMatch['slug']);
                return redirect()->route('books.show', $closestMatch['slug'])
                    ->with('info', "Book with slug '{$slug}' not found. Redirected to closest match.");
            }
            
            return redirect()->route('books.index')->with('error', "Book not found with slug: {$slug}");
        }
        
        logger("BookController::showBySlug - Found book: {$book->title} (ID: {$book->id})");
        
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
            'publication_date' => $validated['publication_date'],
            'format' => $validated['format'],
            'language' => $validated['language'],
            'cover_image' => 'book_stock.png',
            'slug' => Str::slug($validated['title']),
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
            $bookData['cover_image'] = $filename;
        }
        
        $book = Book::create($bookData);
        
        return redirect()->route('books.show', $book)
            ->with('success', 'Book added successfully.');
    }
    
    public function edit(Book $book)
    {
        return view('books.edit', compact('book'));
    }
    
    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'isbn' => 'nullable|string|max:20',
            'isbn13' => 'nullable|string|max:20',
            'asin' => 'nullable|string|max:20',
            'num_pages' => 'nullable|integer',
            'publication_date' => 'nullable|date',
            'format' => 'nullable|string|max:50',
            'language' => 'nullable|string|max:50',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'user_rating' => 'nullable|integer|min:0|max:100',
            'date_added' => 'nullable|date',
            'date_started' => 'nullable|date',
            'date_read' => 'nullable|date',
            'reading_status' => 'nullable|string|in:unread,next_up,reading,read',
            'shelf' => 'nullable|string',
        ]);
        
        // Handle reading status if it's set
        if ($request->has('reading_status')) {
            switch ($validated['reading_status']) {
                case 'unread':
                case 'next_up':
                    $validated['date_started'] = null;
                    $validated['date_read'] = null;
                    break;
                case 'reading':
                    $validated['date_started'] = $validated['date_started'] ?? now()->toDateString();
                    $validated['date_read'] = null;
                    break;
                case 'read':
                    $validated['date_started'] = $validated['date_started'] ?? now()->toDateString();
                    $validated['date_read'] = $validated['date_read'] ?? now()->toDateString();
                    break;
            }
        }
        
        // Handle shelf status
        if ($request->has('shelf')) {
            // Convert shelf to owned boolean for database storage
            if (in_array($validated['shelf'], ['1', 'owned', 1])) {
                $validated['owned'] = true;
            } elseif (in_array($validated['shelf'], ['0', 'not_owned', 0])) {
                $validated['owned'] = false;
            } else {
                // For custom shelf values like 'wish', 'borrowed', etc.
                // We'll store a string in the 'shelf' field when we add it to the database
                // For now, just set 'owned' to false for these values
                $validated['owned'] = false;
            }
        }
        
        // Update book with validated data
        $book->fill([
            'title' => $validated['title'],
            'author' => $validated['author'],
            'description' => $validated['description'],
            'isbn' => $validated['isbn'],
            'isbn13' => $validated['isbn13'] ?? null,
            'asin' => $validated['asin'] ?? null,
            'num_pages' => $validated['num_pages'] ?? null,
            'publication_date' => $validated['publication_date'],
            'format' => $validated['format'],
            'language' => $validated['language'],
            'user_rating' => $validated['user_rating'],
            'date_added' => $validated['date_added'],
            'date_started' => $validated['date_started'],
            'date_read' => $validated['date_read'],
            'owned' => $validated['owned'] ?? $book->owned,
        ]);
        
        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            $coverImage = $request->file('cover_image');
            
            // Create a more readable filename with timestamp and sanitized book title
            $safeTitle = Str::slug($book->title);
            $filename = time() . '_' . $safeTitle . '.' . $coverImage->getClientOriginalExtension();
            
            // Ensure the storage directory exists
            if (!Storage::exists('public/book-covers')) {
                Storage::makeDirectory('public/book-covers');
            }
            
            // Delete old cover image if it's not the default
            if ($book->cover_image && $book->cover_image !== 'default-book-cover.jpg' && $book->cover_image !== 'book_stock.png') {
                Storage::delete('public/book-covers/' . $book->cover_image);
            }
            
            // Store the image
            $coverImage->storeAs('public/book-covers', $filename);
            $book->cover_image = $filename;
            
            // Add success message about the image
            session()->flash('image_success', 'Book cover image was successfully updated.');
        }
        
        // If the title changed, regenerate the slug
        if ($book->isDirty('title')) {
            $book->slug = $book->generateSlug();
        }
        
        $book->save();
        
        return redirect()->route('books.show', $book->slug)
            ->with('success', 'Book updated successfully.');
    }
    
    public function destroy(Book $book)
    {
        // Delete cover image if it's not the default
        if ($book->cover_image && $book->cover_image !== 'default-book-cover.jpg' && $book->cover_image !== 'book_stock.png') {
            Storage::delete('public/book-covers/' . $book->cover_image);
        }
        
        $book->delete();
        
        return redirect()->route('books.index')
            ->with('success', 'Book deleted successfully.');
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
        
        $importedCount = $this->importFromGoodreads($goodreadsData);
        
        return redirect()->route('books.index')
            ->with('success', "{$importedCount} books imported successfully.");
    }
    
    private function importFromGoodreads(array $goodreadsData)
    {
        $importedCount = 0;
        
        foreach ($goodreadsData as $entry) {
            // Map Goodreads data to our Book model
            $title = $entry['title'] ?? 'Unknown Title';
            
            // Check if book already exists by ISBN, ISBN13, or title+author combination
            $existingBook = Book::where(function($query) use ($entry) {
                    if (!empty($entry['isbn'])) {
                        $query->where('isbn', $entry['isbn']);
                    }
                })
                ->orWhere(function($query) use ($entry) {
                    if (!empty($entry['isbn13'])) {
                        $query->where('isbn13', $entry['isbn13']);
                    }
                })
                ->orWhere(function($query) use ($entry, $title) {
                    if (!empty($title) && !empty($entry['author'])) {
                        $query->where('title', $title)
                              ->where('author', $entry['author'] ?? 'Unknown Author');
                    }
                })
                ->first();
            
            if ($existingBook) {
                // Update existing book
                $existingBook->update([
                    'title' => $title,
                    'author' => $entry['author'] ?? 'Unknown Author',
                    'description' => $existingBook->description,
                    'num_pages' => $entry['num_pages'] ?? null,
                    'publication_date' => $this->formatDate($entry['pub_date']),
                    'format' => $entry['format'] ?? null,
                    'user_rating' => $entry['user_rating'] ?? null,
                    'avg_rating' => $entry['avg_rating'] ?? null,
                    'date_added' => $this->formatDate($entry['date_added']),
                    'date_started' => $this->formatDate($entry['date_started']),
                    'date_read' => $this->formatDate($entry['date_read']),
                    'owned' => $entry['owned'] === 'owned',
                    'language' => $entry['language'] ?? '',
                ]);
                
                $importedCount++;
                continue;
            }
            
            // Create new book
            Book::create([
                'title' => $title,
                'author' => $entry['author'] ?? 'Unknown Author',
                'description' => null,
                'isbn' => $entry['isbn'] ?? null,
                'isbn13' => $entry['isbn13'] ?? null,
                'asin' => $entry['asin'] ?? null,
                'num_pages' => $entry['num_pages'] ?? null,
                'cover_image' => 'book_stock.png', // Default cover
                'publication_date' => $this->formatDate($entry['pub_date']),
                'format' => $entry['format'] ?? null,
                'user_rating' => $entry['user_rating'] ?? null,
                'avg_rating' => $entry['avg_rating'] ?? null,
                'date_added' => $this->formatDate($entry['date_added']),
                'date_started' => $this->formatDate($entry['date_started']),
                'date_read' => $this->formatDate($entry['date_read']),
                'owned' => $entry['owned'] === 'owned',
                'language' => $entry['language'] ?? '',
                'slug' => Str::slug($title),
            ]);
            
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