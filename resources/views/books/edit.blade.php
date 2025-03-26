@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('books.show', $book->id) }}" class="btn btn-secondary">
                <span class="material-icons">arrow_back</span> Back to Book
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Edit Book</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('books.update', $book->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Title</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $book->title) }}" required>
                                    
                                    @error('title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="author" class="form-label">Author</label>
                                    <input type="text" class="form-control @error('author') is-invalid @enderror" id="author" name="author" value="{{ old('author', $book->author) }}" required>
                                    
                                    @error('author')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3 text-center">
                                    <label for="cover_image" class="form-label d-block">Cover Image</label>
                                    <img src="{{ $book->getCoverImageUrl() }}" alt="{{ $book->title }}" class="img-thumbnail mb-2" style="max-height: 150px;" id="cover_preview">
                                    <input type="file" class="form-control @error('cover_image') is-invalid @enderror" id="cover_image" name="cover_image" accept="image/jpeg,image/png,image/gif,image/webp">
                                    <small class="form-text text-muted">Accepted formats: JPEG, PNG, GIF, WEBP (max: 2MB)</small>
                                    
                                    @error('cover_image')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $book->description) }}</textarea>
                            
                            @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="isbn" class="form-label">ISBN</label>
                                    <input type="text" class="form-control @error('isbn') is-invalid @enderror" id="isbn" name="isbn" value="{{ old('isbn', $book->isbn) }}">
                                    
                                    @error('isbn')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="publication_date" class="form-label">Publication Date</label>
                                    <input type="date" class="form-control @error('publication_date') is-invalid @enderror" id="publication_date" name="publication_date" value="{{ old('publication_date', $book->publicationDate) }}">
                                    
                                    @error('publication_date')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="format" class="form-label">Format</label>
                                    <input type="text" class="form-control @error('format') is-invalid @enderror" id="format" name="format" value="{{ old('format', $book->format) }}">
                                    
                                    @error('format')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="language" class="form-label">Language</label>
                                    <input type="text" class="form-control @error('language') is-invalid @enderror" id="language" name="language" value="{{ old('language', $book->language) }}">
                                    
                                    @error('language')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="user_rating" class="form-label">Your Rating (0-100)</label>
                            <input type="range" class="form-range @error('user_rating') is-invalid @enderror" id="user_rating" name="user_rating" min="0" max="100" step="20" value="{{ old('user_rating', $book->userRating) }}">
                            <div class="d-flex justify-content-between">
                                <span>0</span>
                                <span>20</span>
                                <span>40</span>
                                <span>60</span>
                                <span>80</span>
                                <span>100</span>
                            </div>
                            
                            @error('user_rating')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="mb-0 text-center">
                            <button type="submit" class="btn btn-primary">
                                <span class="material-icons">save</span> Save Changes
                            </button>
                            <a href="{{ route('books.show', $book->id) }}" class="btn btn-secondary ms-2">
                                <span class="material-icons">cancel</span> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const coverInput = document.getElementById('cover_image');
        const coverPreview = document.getElementById('cover_preview');
        
        if (coverInput && coverPreview) {
            coverInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        coverPreview.src = e.target.result;
                    };
                    
                    reader.readAsDataURL(this.files[0]);
                }
            });
        }
    });
</script>
@endsection