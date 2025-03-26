<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BookController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReadingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Redirect home to login form if not authenticated
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Auth Routes
Auth::routes();

// Dashboard
Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Reading Routes (main reading section)
    Route::get('/reading', [ReadingController::class, 'index'])->name('reading.index');
    
    // Book Routes
    // List and create routes
    Route::get('/books', [BookController::class, 'index'])->name('books.index');
    Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
    
    // Import routes
    Route::get('/books/import/form', [BookController::class, 'importForm'])->name('books.import.form');
    Route::post('/books/import', [BookController::class, 'import'])->name('books.import');
    
    // Store new book
    Route::post('/books', [BookController::class, 'store'])->name('books.store');
    
    // ID-based routes for editing and management
    Route::get('/books/id/{id}', [BookController::class, 'show'])->name('books.show.id');
    Route::get('/books/edit/{book}', [BookController::class, 'edit'])->name('books.edit');
    Route::put('/books/update/{book}', [BookController::class, 'update'])->name('books.update');
    Route::delete('/books/delete/{book}', [BookController::class, 'destroy'])->name('books.destroy');
    
    // Slug-based route for book details (must be last because it's a catch-all)
    Route::get('/books/{book:slug}', [BookController::class, 'show'])->name('books.show');
});