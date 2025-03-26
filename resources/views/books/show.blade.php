@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('reading.index') }}">Reading</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('books.index') }}">Books</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $book->title }}</li>
                </ol>
            </nav>
        </div>
    </div>

    @if (session('success'))
        <div class="alert custom-alert success-alert alert-dismissible fade show" role="alert">
            <div class="d-flex">
                <span class="material-icons me-2">check_circle</span>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close custom-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if (session('info'))
        <div class="alert custom-alert info-alert alert-dismissible fade show" role="alert">
            <div class="d-flex">
                <span class="material-icons me-2">info</span>
                <div>{{ session('info') }}</div>
            </div>
            <button type="button" class="btn-close custom-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if (session('image_success'))
        <div class="alert custom-alert image-alert alert-dismissible fade show" role="alert">
            <div class="d-flex">
                <span class="material-icons me-2">image</span>
                <div>{{ session('image_success') }}</div>
            </div>
            <button type="button" class="btn-close custom-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-4">
            <div class="card book-cover-card mb-4">
                <div class="card-cover-wrapper">
                    <img src="{{ $book->getCoverImageUrl() }}" class="book-detail-cover" alt="{{ $book->title }}">
                </div>
                <div class="card-body text-center">
                    <div class="book-url-display mb-3 text-center">
                        <span class="book-url">{{ url('/books/' . $book->getSlug()) }}</span>
                        <button class="copy-url-btn" onclick="copyBookUrl()" title="Copy URL">
                            <span class="material-icons">content_copy</span>
                        </button>
                    </div>
                    <div class="action-buttons">
                        <a href="{{ route('books.edit', $book->id) }}" class="btn btn-edit me-2">
                            <span class="material-icons">edit</span> Edit
                        </a>
                        <button type="button" class="btn btn-delete" data-bs-toggle="modal" data-bs-target="#deleteBookModal">
                            <span class="material-icons">delete</span> Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card book-detail-card">
                <div class="card-header">
                    <h1 class="book-title">{{ $book->title }}</h1>
                    <h3 class="book-author">{{ $book->author }}</h3>
                    <div class="book-status-wrapper mt-2">
                        <span class="status-badge status-{{ strtolower($book->getReadStatus()) }}">
                            {{ $book->getReadStatus() }}
                        </span>
                        @if($book->dateStarted && !$book->dateRead)
                            <div class="progress mt-2" style="height: 6px; width: 150px;">
                                <div class="progress-bar" role="progressbar" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <!-- Book identifiers -->
                            <div class="detail-section mb-4">
                                <h4 class="section-subheading">Identifiers</h4>
                                
                                @if($book->isbn13)
                                <div class="book-detail-item">
                                    <div class="book-detail-label">ISBN-13</div>
                                    <div class="book-detail-value">{{ $book->isbn13 }}</div>
                                </div>
                                @endif
                                
                                @if($book->isbn)
                                <div class="book-detail-item">
                                    <div class="book-detail-label">ISBN-10</div>
                                    <div class="book-detail-value">{{ $book->isbn }}</div>
                                </div>
                                @endif
                                
                                @if($book->asin)
                                <div class="book-detail-item">
                                    <div class="book-detail-label">ASIN</div>
                                    <div class="book-detail-value">{{ $book->asin }}</div>
                                </div>
                                @endif
                            </div>
                            
                            <!-- Publication details -->
                            <div class="detail-section mb-4">
                                <h4 class="section-subheading">Publication</h4>
                                
                                @if($book->publicationDate)
                                <div class="book-detail-item">
                                    <div class="book-detail-label">Publication Date</div>
                                    <div class="book-detail-value">{{ date('F j, Y', strtotime($book->publicationDate)) }}</div>
                                </div>
                                @endif
                                
                                @if($book->format)
                                <div class="book-detail-item">
                                    <div class="book-detail-label">Format</div>
                                    <div class="book-detail-value">
                                        <span class="format-badge">{{ $book->format }}</span>
                                    </div>
                                </div>
                                @endif
                                
                                @if($book->numPages)
                                <div class="book-detail-item">
                                    <div class="book-detail-label">Pages</div>
                                    <div class="book-detail-value">{{ $book->numPages }}</div>
                                </div>
                                @endif
                                
                                @if($book->language)
                                <div class="book-detail-item">
                                    <div class="book-detail-label">Language</div>
                                    <div class="book-detail-value">{{ $book->language ?: 'Not specified' }}</div>
                                </div>
                                @endif
                            </div>
                            
                            <!-- Rating information -->
                            <div class="detail-section">
                                <h4 class="section-subheading">Ratings</h4>
                                
                                <div class="book-detail-item">
                                    <div class="book-detail-label">Your Rating</div>
                                    <div class="book-detail-value rating">
                                        {!! $book->getFormattedRating() !!}
                                        @if($book->userRating)
                                            <span class="rating-number">({{ number_format($book->userRating/20, 1) }}/5)</span>
                                        @endif
                                    </div>
                                </div>
                                
                                @if($book->avgRating)
                                <div class="book-detail-item">
                                    <div class="book-detail-label">Average Rating</div>
                                    <div class="book-detail-value">
                                        <div class="rating">
                                            @php
                                                $avgStars = round($book->avgRating / 20);
                                                echo str_repeat('★', $avgStars) . str_repeat('☆', 5 - $avgStars);
                                            @endphp
                                        </div>
                                        <span class="rating-number">({{ number_format($book->avgRating/20, 1) }}/5)</span>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <!-- Reading status -->
                            <div class="detail-section mb-4">
                                <h4 class="section-subheading">Reading Status</h4>
                                
                                <div class="book-detail-item">
                                    <div class="book-detail-label">Status</div>
                                    <div class="book-detail-value">
                                        <span class="status-badge status-{{ strtolower($book->getReadStatus()) }}">
                                            {{ $book->getReadStatus() }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="book-detail-item">
                                    <div class="book-detail-label">Ownership</div>
                                    <div class="book-detail-value">
                                        @if($book->owned)
                                            <span class="owned-badge">Owned</span>
                                        @else
                                            <span class="not-owned-badge">Not Owned</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Reading timeline -->
                            <div class="detail-section">
                                <h4 class="section-subheading">Timeline</h4>
                                
                                @if($book->dateAdded)
                                <div class="book-detail-item">
                                    <div class="book-detail-label">Added to Library</div>
                                    <div class="book-detail-value">{{ date('F j, Y', strtotime($book->dateAdded)) }}</div>
                                </div>
                                @endif
                                
                                @if($book->dateStarted)
                                <div class="book-detail-item">
                                    <div class="book-detail-label">Started Reading</div>
                                    <div class="book-detail-value highlight-date">{{ date('F j, Y', strtotime($book->dateStarted)) }}</div>
                                </div>
                                @endif
                                
                                @if($book->dateRead)
                                <div class="book-detail-item">
                                    <div class="book-detail-label">Finished Reading</div>
                                    <div class="book-detail-value highlight-date">{{ date('F j, Y', strtotime($book->dateRead)) }}</div>
                                </div>
                                
                                @if($book->dateStarted && $book->dateRead)
                                    @php
                                        $start = new DateTime($book->dateStarted);
                                        $end = new DateTime($book->dateRead);
                                        $interval = $start->diff($end);
                                        $readingDays = $interval->days;
                                    @endphp
                                    <div class="book-detail-item">
                                        <div class="book-detail-label">Reading Time</div>
                                        <div class="book-detail-value">
                                            {{ $readingDays }} {{ $readingDays == 1 ? 'day' : 'days' }}
                                        </div>
                                    </div>
                                @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    @if($book->description)
                    <div class="book-description-section">
                        <h4 class="section-title">Description</h4>
                        <div class="book-description">{{ $book->description }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Gruvbox dark theme for book details */
    body {
        background-color: #282828;
        color: #ebdbb2;
    }
    
    .card {
        background-color: #3c3836;
        border-color: #504945;
    }
    
    .card-header {
        background-color: #504945 !important;
        border-bottom-color: #665c54;
        padding: 1.5rem;
    }
    
    /* Breadcrumb styling */
    .breadcrumb {
        font-size: 0.875rem;
        background-color: transparent;
        padding: 0;
        margin: 0;
    }
    
    .breadcrumb-item a {
        color: #a89984;
        text-decoration: none;
    }
    
    .breadcrumb-item a:hover {
        color: #8ec07c;
        text-decoration: none;
    }
    
    .breadcrumb-item.active {
        color: #ebdbb2;
        font-weight: 500;
    }
    
    /* Book cover styling */
    .book-cover-card {
        border-radius: 8px;
        overflow: hidden;
    }
    
    .card-cover-wrapper {
        padding: 1.5rem;
        display: flex;
        justify-content: center;
        background-color: #32302f;
    }
    
    .book-detail-cover {
        max-height: 400px;
        object-fit: contain;
        border-radius: 4px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.25);
        border: 1px solid #504945;
    }
    
    /* Book details */
    .book-detail-card {
        border-radius: 8px;
        height: 100%;
    }
    
    .book-title {
        color: #fbf1c7;
        font-size: 2rem;
        margin-bottom: 0.25rem;
        font-weight: 500;
    }
    
    .book-author {
        color: #a89984;
        font-size: 1.25rem;
        font-weight: 400;
        margin-bottom: 1rem;
    }
    
    .book-detail-item {
        margin-bottom: 1.25rem;
    }
    
    .book-detail-label {
        color: #a89984;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
    }
    
    .book-detail-value {
        color: #ebdbb2;
        font-size: 1rem;
    }
    
    .highlight-date {
        color: #b8bb26;
        font-weight: 500;
    }
    
    .section-title {
        color: #fbf1c7;
        font-size: 1.25rem;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #504945;
    }
    
    .section-subheading {
        color: #fabd2f;
        font-size: 1rem;
        margin-bottom: 1rem;
        padding-bottom: 0.25rem;
        border-bottom: 1px solid #504945;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 500;
    }
    
    .detail-section {
        margin-bottom: 1.5rem;
    }
    
    .rating-number {
        color: #a89984;
        font-size: 0.8rem;
        margin-left: 0.5rem;
        vertical-align: middle;
    }
    
    .owned-badge, .not-owned-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .owned-badge {
        background-color: rgba(104, 157, 106, 0.2);
        color: #8ec07c;
        border: 1px solid #689d6a;
    }
    
    .not-owned-badge {
        background-color: rgba(204, 36, 29, 0.2);
        color: #fb4934;
        border: 1px solid #cc241d;
    }
    
    .book-description {
        color: #d5c4a1;
        line-height: 1.6;
    }
    
    /* URL display */
    .book-url-display {
        background-color: #32302f;
        border-radius: 4px;
        padding: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: monospace;
    }
    
    .book-url {
        color: #a89984;
        font-size: 0.8rem;
        margin-right: 0.5rem;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    .copy-url-btn {
        background: none;
        border: none;
        color: #83a598;
        cursor: pointer;
        transition: color 0.2s;
    }
    
    .copy-url-btn:hover {
        color: #8ec07c;
    }
    
    /* Action buttons */
    .action-buttons {
        display: flex;
        justify-content: center;
    }
    
    .btn-edit {
        background-color: #83a598;
        border-color: #83a598;
        color: #282828;
    }
    
    .btn-edit:hover {
        background-color: #8ec07c;
        border-color: #8ec07c;
        color: #282828;
    }
    
    .btn-delete {
        background-color: #cc241d;
        border-color: #cc241d;
        color: #fbf1c7;
    }
    
    .btn-delete:hover {
        background-color: #fb4934;
        border-color: #fb4934;
        color: #fbf1c7;
    }
    
    /* Status badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .status-read {
        background-color: rgba(152, 151, 26, 0.2);
        color: #b8bb26;
        border: 1px solid #98971a;
    }
    
    .status-reading {
        background-color: rgba(214, 93, 14, 0.2);
        color: #fe8019;
        border: 1px solid #d65d0e;
    }
    
    .status-unread {
        background-color: rgba(168, 153, 132, 0.2);
        color: #ebdbb2;
        border: 1px solid #665c54;
    }
    
    /* Rating stars */
    .rating {
        color: #fabd2f;
        font-size: 1.2rem;
        letter-spacing: 2px;
    }
    
    /* Format badge */
    .format-badge {
        display: inline-block;
        background-color: #504945;
        color: #ebdbb2;
        font-size: 0.875rem;
        padding: 0.25rem 0.75rem;
        border-radius: 4px;
    }
    
    /* Alerts */
    .custom-alert {
        border-radius: 6px;
        padding: 1rem;
        margin-bottom: 1.5rem;
        border-left-width: 4px;
        border-left-style: solid;
    }
    
    .success-alert {
        background-color: rgba(152, 151, 26, 0.1);
        color: #b8bb26;
        border-left-color: #b8bb26;
    }
    
    .info-alert {
        background-color: rgba(131, 165, 152, 0.1);
        color: #8ec07c;
        border-left-color: #8ec07c;
    }
    
    .image-alert {
        background-color: rgba(180, 142, 173, 0.1);
        color: #d3869b;
        border-left-color: #d3869b;
    }
    
    .custom-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }
    
    /* Progress bar */
    .progress {
        border-radius: 10px;
        background-color: #504945;
    }
    
    .progress-bar {
        background-color: #b8bb26;
    }
</style>

<script>
    function copyBookUrl() {
        const url = "{{ url('/books/' . $book->getSlug()) }}";
        navigator.clipboard.writeText(url).then(() => {
            const copyBtn = document.querySelector('.copy-url-btn');
            const originalIcon = copyBtn.innerHTML;
            
            copyBtn.innerHTML = '<span class="material-icons">check</span>';
            setTimeout(() => {
                copyBtn.innerHTML = originalIcon;
            }, 2000);
        });
    }
</script>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteBookModal" tabindex="-1" aria-labelledby="deleteBookModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content modal-dark">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteBookModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close custom-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex align-items-center">
                    <span class="material-icons text-danger me-2">warning</span>
                    <p class="mb-0">Are you sure you want to delete "<strong>{{ $book->title }}</strong>"? This action cannot be undone.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('books.destroy', $book->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-delete">Delete Book</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* Modal styling */
    .modal-dark .modal-content {
        background-color: #3c3836;
        color: #ebdbb2;
        border-color: #504945;
    }
    
    .modal-dark .modal-header,
    .modal-dark .modal-footer {
        border-color: #504945;
        background-color: #32302f;
    }
    
    .modal-dark .modal-title {
        color: #fbf1c7;
    }
    
    .modal-dark .btn-secondary {
        background-color: #504945;
        border-color: #665c54;
        color: #ebdbb2;
    }
    
    .modal-dark .btn-secondary:hover {
        background-color: #665c54;
        color: #fbf1c7;
    }
    
    .modal-dark .text-danger {
        color: #fb4934 !important;
    }
</style>
@endsection