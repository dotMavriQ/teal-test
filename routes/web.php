<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileAuthController;

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

// Redirect home to login form
Route::get('/', function () {
    return redirect()->route('file.login.form');
});

// File Auth Routes
Route::get('/login', [FileAuthController::class, 'showLoginForm'])->name('file.login.form');
Route::post('/login', [FileAuthController::class, 'login'])->name('file.login');
Route::post('/logout', [FileAuthController::class, 'logout'])->name('file.logout');
Route::get('/dashboard', [FileAuthController::class, 'dashboard'])->name('dashboard');

// Reading Routes (main reading section)
Route::get('/reading', [App\Http\Controllers\ReadingController::class, 'index'])->name('reading.index');

// Book Routes
Route::group(['middleware' => 'web'], function () {
    // List and create routes
    Route::get('/books', [App\Http\Controllers\BookController::class, 'index'])->name('books.index');
    Route::get('/books/create', [App\Http\Controllers\BookController::class, 'create'])->name('books.create');
    
    // Import routes
    Route::get('/books/import/form', [App\Http\Controllers\BookController::class, 'importForm'])->name('books.import.form');
    Route::post('/books/import', [App\Http\Controllers\BookController::class, 'import'])->name('books.import');
    
    // Store new book
    Route::post('/books', [App\Http\Controllers\BookController::class, 'store'])->name('books.store');
    
    // ID-based routes for editing and management
    Route::get('/books/id/{id}', [App\Http\Controllers\BookController::class, 'show'])->name('books.show.id');
    Route::get('/books/edit/{id}', [App\Http\Controllers\BookController::class, 'edit'])->name('books.edit');
    Route::put('/books/update/{id}', [App\Http\Controllers\BookController::class, 'update'])->name('books.update');
    Route::delete('/books/delete/{id}', [App\Http\Controllers\BookController::class, 'destroy'])->name('books.destroy');
    
    // Slug-based route for book details (must be last because it's a catch-all)
    Route::get('/books/{slug}', [App\Http\Controllers\BookController::class, 'showBySlug'])->name('books.show');
});

// Disable traditional Laravel Auth Routes
// Auth::routes();