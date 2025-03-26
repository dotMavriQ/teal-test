@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="mb-4">Reading Library</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <h2 class="card-title">Books</h2>
                    <p class="card-text">Access your collection of books, import from Goodreads, and manage your reading list.</p>
                    <a href="{{ route('books.index') }}" class="btn btn-primary btn-lg">
                        <span class="material-icons">menu_book</span> Browse Books
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <h2 class="card-title">Comics</h2>
                    <p class="card-text">Access your collection of comics, graphic novels, and manga.</p>
                    <a href="#" class="btn btn-secondary btn-lg">
                        <span class="material-icons">browse</span> Coming Soon
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection