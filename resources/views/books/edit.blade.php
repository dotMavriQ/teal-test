@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('books.show', $book->slug) }}" class="btn btn-secondary">
                <span class="material-icons">arrow_back</span> Back to Book
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h4>Edit Book</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('books.update', $book->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-4">
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
                                    <img src="{{ $book->getCoverImageUrl() }}" alt="{{ $book->title }}" class="img-thumbnail mb-3" style="max-height: 190px; max-width: 150px; object-fit: cover;" id="cover_preview">
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
                        
                        <!-- Reading Status Section -->
                        <div class="card mb-4">
                            <div class="card-header bg-secondary text-white">
                                Reading Status
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="reading_status" class="form-label">Status</label>
                                            <select class="form-select @error('reading_status') is-invalid @enderror" id="reading_status" name="reading_status">
                                                <option value="unread" {{ $book->date_read == null && $book->date_started == null ? 'selected' : '' }}>To-Read</option>
                                                <option value="next_up" {{ $book->date_read == null && $book->date_started == null && old('reading_status') == 'next_up' ? 'selected' : '' }}>Next Up</option>
                                                <option value="reading" {{ $book->date_started != null && $book->date_read == null ? 'selected' : '' }}>Reading</option>
                                                <option value="read" {{ $book->date_read != null ? 'selected' : '' }}>Read</option>
                                            </select>
                                            
                                            @error('reading_status')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="shelf" class="form-label">Shelf</label>
                                            <select class="form-select @error('shelf') is-invalid @enderror" id="shelf" name="shelf">
                                                <option value="0" {{ $book->owned == 0 ? 'selected' : '' }}>Not Owned</option>
                                                <option value="1" {{ $book->owned == 1 ? 'selected' : '' }}>Owned</option>
                                                <option value="wish" {{ old('shelf') == 'wish' ? 'selected' : '' }}>Wishlist</option>
                                                <option value="borrowed" {{ old('shelf') == 'borrowed' ? 'selected' : '' }}>Borrowed</option>
                                                <option value="loaned" {{ old('shelf') == 'loaned' ? 'selected' : '' }}>Loaned Out</option>
                                            </select>
                                            
                                            @error('shelf')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="date_added" class="form-label">Date Added</label>
                                            <input type="date" class="form-control @error('date_added') is-invalid @enderror" id="date_added" name="date_added" value="{{ old('date_added', $book->date_added) }}">
                                            
                                            @error('date_added')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="date_started" class="form-label">Date Started</label>
                                            <input type="date" class="form-control @error('date_started') is-invalid @enderror" id="date_started" name="date_started" value="{{ old('date_started', $book->date_started) }}">
                                            
                                            @error('date_started')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="date_read" class="form-label">Date Finished</label>
                                            <input type="date" class="form-control @error('date_read') is-invalid @enderror" id="date_read" name="date_read" value="{{ old('date_read', $book->date_read) }}">
                                            
                                            @error('date_read')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="user_rating" class="form-label">Your Rating</label>
                                    <input type="range" class="form-range @error('user_rating') is-invalid @enderror" id="user_rating" name="user_rating" min="0" max="100" step="20" value="{{ old('user_rating', $book->user_rating) }}">
                                    <div class="d-flex justify-content-between rating-labels">
                                        <span>Not Rated</span>
                                        <span>★</span>
                                        <span>★★</span>
                                        <span>★★★</span>
                                        <span>★★★★</span>
                                        <span>★★★★★</span>
                                    </div>
                                    
                                    @error('user_rating')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Book Details Section -->
                        <div class="card mb-4">
                            <div class="card-header bg-secondary text-white">
                                Book Details
                            </div>
                            <div class="card-body">
                                <div class="mb-4">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5">{{ old('description', $book->description) }}</textarea>
                                    
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
                                            <label for="isbn13" class="form-label">ISBN-13</label>
                                            <input type="text" class="form-control @error('isbn13') is-invalid @enderror" id="isbn13" name="isbn13" value="{{ old('isbn13', $book->isbn13) }}">
                                            
                                            @error('isbn13')
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
                                            <label for="publication_date" class="form-label">Publication Date</label>
                                            <input type="date" class="form-control @error('publication_date') is-invalid @enderror" id="publication_date" name="publication_date" value="{{ old('publication_date', $book->publication_date) }}">
                                            
                                            @error('publication_date')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="num_pages" class="form-label">Number of Pages</label>
                                            <input type="number" class="form-control @error('num_pages') is-invalid @enderror" id="num_pages" name="num_pages" value="{{ old('num_pages', $book->num_pages) }}">
                                            
                                            @error('num_pages')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="format" class="form-label">Format</label>
                                            <select class="form-select @error('format') is-invalid @enderror" id="format" name="format">
                                                <option value="" {{ $book->format == null ? 'selected' : '' }}>Select Format</option>
                                                <option value="Paperback" {{ $book->format == 'Paperback' ? 'selected' : '' }}>Paperback</option>
                                                <option value="Hardcover" {{ $book->format == 'Hardcover' ? 'selected' : '' }}>Hardcover</option>
                                                <option value="Kindle Edition" {{ $book->format == 'Kindle Edition' ? 'selected' : '' }}>Kindle Edition</option>
                                                <option value="ebook" {{ $book->format == 'ebook' ? 'selected' : '' }}>eBook</option>
                                                <option value="Audiobook" {{ $book->format == 'Audiobook' ? 'selected' : '' }}>Audiobook</option>
                                                <option value="Unknown Binding" {{ $book->format == 'Unknown Binding' ? 'selected' : '' }}>Unknown Binding</option>
                                            </select>
                                            
                                            @error('format')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="language" class="form-label">Language</label>
                                            <select class="form-select @error('language') is-invalid @enderror" id="language" name="language">
                                                <option value="" {{ $book->language == null ? 'selected' : '' }}>Select Language</option>
                                                <option value="English" {{ $book->language == 'English' ? 'selected' : '' }}>English</option>
                                                <option value="Swedish" {{ $book->language == 'Swedish' ? 'selected' : '' }}>Swedish</option>
                                                <option value="German" {{ $book->language == 'German' ? 'selected' : '' }}>German</option>
                                                <option value="French" {{ $book->language == 'French' ? 'selected' : '' }}>French</option>
                                                <option value="Spanish" {{ $book->language == 'Spanish' ? 'selected' : '' }}>Spanish</option>
                                                <option value="Italian" {{ $book->language == 'Italian' ? 'selected' : '' }}>Italian</option>
                                                <option value="Japanese" {{ $book->language == 'Japanese' ? 'selected' : '' }}>Japanese</option>
                                            </select>
                                            
                                            @error('language')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="asin" class="form-label">ASIN</label>
                                            <input type="text" class="form-control @error('asin') is-invalid @enderror" id="asin" name="asin" value="{{ old('asin', $book->asin) }}">
                                            
                                            @error('asin')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-0 text-center">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <span class="material-icons">save</span> Save Changes
                            </button>
                            <a href="{{ route('books.show', $book->slug) }}" class="btn btn-secondary btn-lg ms-2">
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
        // Cover image preview handler
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
        
        // Reading status handler
        const readingStatusSelect = document.getElementById('reading_status');
        const dateStartedInput = document.getElementById('date_started');
        const dateReadInput = document.getElementById('date_read');
        
        if (readingStatusSelect) {
            readingStatusSelect.addEventListener('change', function() {
                const status = this.value;
                const currentDate = new Date().toISOString().split('T')[0];
                
                // Update date fields based on status
                switch(status) {
                    case 'reading':
                        // If currently reading, set start date if not already set
                        if (!dateStartedInput.value) {
                            dateStartedInput.value = currentDate;
                        }
                        // Clear the read date
                        dateReadInput.value = '';
                        break;
                        
                    case 'read':
                        // If read, set both dates if not already set
                        if (!dateStartedInput.value) {
                            dateStartedInput.value = currentDate;
                        }
                        if (!dateReadInput.value) {
                            dateReadInput.value = currentDate;
                        }
                        break;
                        
                    case 'unread':
                    case 'next_up':
                        // If unread or next up, clear both dates
                        dateStartedInput.value = '';
                        dateReadInput.value = '';
                        break;
                }
            });
        }
        
        // Rating display handler
        const ratingSlider = document.getElementById('user_rating');
        const ratingLabels = document.querySelectorAll('.rating-labels span');
        
        if (ratingSlider) {
            // Initialize the rating display
            updateRatingLabels(ratingSlider.value);
            
            ratingSlider.addEventListener('input', function() {
                updateRatingLabels(this.value);
            });
        }
        
        function updateRatingLabels(value) {
            // Convert the 0-100 range to stars (0-5)
            const stars = Math.round(value / 20);
            
            // Reset all labels to default color
            ratingLabels.forEach((label, index) => {
                if (index === 0) {
                    // Special handling for "Not Rated" label
                    label.style.fontWeight = (stars === 0) ? 'bold' : 'normal';
                    label.style.color = (stars === 0) ? '#b8bb26' : '#a89984';
                } else {
                    // Star labels (1-5)
                    const starIndex = index;
                    label.style.color = (starIndex <= stars) ? '#fabd2f' : '#665c54';
                    label.style.fontWeight = (starIndex === stars) ? 'bold' : 'normal';
                }
            });
        }
    });
</script>
@endsection