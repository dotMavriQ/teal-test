@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('reading.index') }}">Reading</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Books</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 mb-0">Book Collection</h1>
        <div class="btn-toolbar">
            <a href="{{ route('books.create') }}" class="btn btn-success me-2">
                <span class="material-icons align-middle me-1" style="font-size: 20px;">add</span> Add Book
            </a>
            <a href="{{ route('books.import.form') }}" class="btn btn-primary">
                <span class="material-icons align-middle me-1" style="font-size: 20px;">upload_file</span> Import
            </a>
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

    @if (session('error'))
        <div class="alert custom-alert error-alert alert-dismissible fade show" role="alert">
            <div class="d-flex">
                <span class="material-icons me-2">error</span>
                <div>{{ session('error') }}</div>
            </div>
            <button type="button" class="btn-close custom-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    <style>
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
        
        .error-alert {
            background-color: rgba(251, 73, 52, 0.1);
            color: #fb4934;
            border-left-color: #fb4934;
        }
        
        .custom-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }
    </style>

    @if(count($books) > 0)
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3">
                <div class="row align-items-center">
                    <div class="col-md-4 mb-2 mb-md-0">
                        <div class="d-flex align-items-center">
                            <span class="material-icons me-2 text-muted">filter_list</span>
                            <select class="form-select form-select-sm me-2" id="statusFilter">
                                <option value="all" selected>All Statuses</option>
                                <option value="Read">Read</option>
                                <option value="Reading">Reading</option>
                                <option value="Unread">Unread</option>
                            </select>
                            <select class="form-select form-select-sm" id="formatFilter">
                                <option value="all" selected>All Formats</option>
                                <option value="Paperback">Paperback</option>
                                <option value="Hardcover">Hardcover</option>
                                <option value="Kindle Edition">Kindle Edition</option>
                                <option value="Unknown Binding">Unknown Binding</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 mb-2 mb-md-0 text-md-center">
                        <span class="text-muted" id="bookCount">Showing {{ count($books) }} books</span>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <span class="material-icons text-muted" style="font-size: 20px;">search</span>
                            </span>
                            <input type="text" id="searchInput" class="form-control border-start-0 ps-0" placeholder="Search books..." aria-label="Search books">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0" id="booksTable">
                        <thead>
                            <tr>
                                <th class="ps-4" style="width: 100px">Cover</th>
                                <th class="sortable fw-medium" data-sort="title">
                                    <div class="d-flex align-items-center">
                                        Title <span class="material-icons sort-icon ms-1" style="font-size: 18px;">unfold_more</span>
                                    </div>
                                </th>
                                <th class="sortable fw-medium" data-sort="author">
                                    <div class="d-flex align-items-center">
                                        Author <span class="material-icons sort-icon ms-1" style="font-size: 18px;">unfold_more</span>
                                    </div>
                                </th>
                                <th class="sortable fw-medium" data-sort="format">
                                    <div class="d-flex align-items-center">
                                        Format <span class="material-icons sort-icon ms-1" style="font-size: 18px;">unfold_more</span>
                                    </div>
                                </th>
                                <th class="sortable fw-medium" data-sort="published">
                                    <div class="d-flex align-items-center">
                                        Year <span class="material-icons sort-icon ms-1" style="font-size: 18px;">unfold_more</span>
                                    </div>
                                </th>
                                <th class="sortable fw-medium" data-sort="dateAdded">
                                    <div class="d-flex align-items-center">
                                        Added <span class="material-icons sort-icon ms-1" style="font-size: 18px;">unfold_more</span>
                                    </div>
                                </th>
                                <th class="sortable fw-medium" data-sort="rating">
                                    <div class="d-flex align-items-center">
                                        Rating <span class="material-icons sort-icon ms-1" style="font-size: 18px;">unfold_more</span>
                                    </div>
                                </th>
                                <th class="sortable fw-medium" data-sort="status">
                                    <div class="d-flex align-items-center">
                                        Status <span class="material-icons sort-icon ms-1" style="font-size: 18px;">unfold_more</span>
                                    </div>
                                </th>
                                <th class="text-center pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            @foreach($books as $book)
                            <tr class="book-row" data-status="{{ $book->getReadStatus() }}" data-format="{{ $book->format ?? 'Unknown' }}">
                                <td class="ps-4">
                                    <a href="{{ route('books.show', $book->getSlug()) }}" class="cover-link">
                                        <img src="{{ $book->getCoverImageUrl() }}" class="img-fluid book-cover rounded shadow-sm" alt="{{ $book->title }}" style="width: 70px; height: 105px; object-fit: cover;">
                                    </a>
                                </td>
                                <td data-sort-value="{{ $book->title }}">
                                    <a href="{{ route('books.show', $book->getSlug()) }}" class="book-title fw-medium d-block text-truncate" style="max-width: 250px;">{{ $book->title }}</a>
                                    @if($book->date_started && !$book->date_read)
                                        <div class="progress mt-2" style="height: 4px; width: 100px;">
                                            <div class="progress-bar" role="progressbar" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    @endif
                                </td>
                                <td data-sort-value="{{ $book->author }}"><span class="author-name">{{ $book->author }}</span></td>
                                <td data-sort-value="{{ $book->format ?? 'zzz' }}">
                                    @if($book->format)
                                        <span class="format-badge">{{ $book->format }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td data-sort-value="{{ $book->publication_date ?? '0' }}">
                                    @if($book->publication_date)
                                        <span class="year-badge">{{ date('Y', strtotime($book->publication_date)) }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td data-sort-value="{{ $book->date_added ?? '0' }}">
                                    @if($book->date_added)
                                        <span class="date-label">{{ date('M j, Y', strtotime($book->date_added)) }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td data-sort-value="{{ $book->user_rating ?? 0 }}">
                                    <div class="rating">
                                        {!! $book->getFormattedRating() !!}
                                    </div>
                                </td>
                                <td data-sort-value="{{ $book->getReadStatus() }}">
                                    <span class="status-badge status-{{ strtolower($book->getReadStatus()) }}">
                                        {{ $book->getReadStatus() }}
                                    </span>
                                </td>
                                <td class="text-center pe-4">
                                    <div class="action-buttons">
                                        <a href="{{ route('books.show', $book->getSlug()) }}" class="btn btn-sm btn-light border me-1" title="View details">
                                            <span class="material-icons" style="font-size: 18px;">visibility</span>
                                        </a>
                                        <a href="{{ route('books.edit', $book->id) }}" class="btn btn-sm btn-light border" title="Edit book">
                                            <span class="material-icons" style="font-size: 18px;">edit</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Sorting functionality
                const table = document.getElementById('booksTable');
                const headers = table.querySelectorAll('th.sortable');
                let currentSort = { column: '', direction: 'asc' };
                
                // Map column names to indices (1-based because the first column is cover)
                const columnMapping = {
                    'title': 1,
                    'author': 2,
                    'format': 3,
                    'published': 4,
                    'dateAdded': 5,
                    'rating': 6,
                    'status': 7
                };
                
                headers.forEach(header => {
                    header.addEventListener('click', () => {
                        const column = header.dataset.sort;
                        const direction = currentSort.column === column && currentSort.direction === 'asc' ? 'desc' : 'asc';
                        
                        // Reset all headers
                        headers.forEach(h => {
                            h.querySelector('.sort-icon').textContent = 'unfold_more';
                        });
                        
                        // Set the current sort icon
                        header.querySelector('.sort-icon').textContent = direction === 'asc' ? 'expand_less' : 'expand_more';
                        
                        // Sort the table
                        sortTable(column, direction);
                        
                        // Update current sort
                        currentSort = { column, direction };
                        
                        // Log for debugging
                        console.log(`Sorted by ${column} in ${direction} order`);
                    });
                });
                
                function sortTable(column, direction) {
                    const tbody = table.querySelector('tbody');
                    const rows = Array.from(tbody.querySelectorAll('tr'));
                    
                    // Convert column name to index
                    const columnIndex = columnMapping[column];
                    
                    if (!columnIndex) {
                        console.error(`Column ${column} not found in mapping`);
                        return;
                    }
                    
                    // Sort the rows
                    rows.sort((a, b) => {
                        // Get the cells
                        const cellsA = a.querySelectorAll('td');
                        const cellsB = b.querySelectorAll('td');
                        
                        if (cellsA.length <= columnIndex || cellsB.length <= columnIndex) {
                            console.error('Row does not have enough cells');
                            return 0;
                        }
                        
                        const cellA = cellsA[columnIndex];
                        const cellB = cellsB[columnIndex];
                        
                        // Get values, prioritizing data-sort-value attributes if they exist
                        let valueA, valueB;
                        
                        if (cellA.hasAttribute('data-sort-value') && cellB.hasAttribute('data-sort-value')) {
                            valueA = cellA.getAttribute('data-sort-value');
                            valueB = cellB.getAttribute('data-sort-value');
                            
                            // Handle empty values
                            if (valueA === '') valueA = null;
                            if (valueB === '') valueB = null;
                            
                            // Handle numeric values
                            if (!isNaN(valueA) && !isNaN(valueB)) {
                                valueA = Number(valueA);
                                valueB = Number(valueB);
                            }
                        } else {
                            // Get text content as a fallback
                            valueA = cellA.textContent.trim();
                            valueB = cellB.textContent.trim();
                        }
                        
                        // Handle special columns
                        if (column === 'title' || column === 'author') {
                            // For text fields, do case-insensitive comparison
                            valueA = (valueA || '').toString().toLowerCase();
                            valueB = (valueB || '').toString().toLowerCase();
                        } else if (column === 'published' || column === 'dateAdded') {
                            // For dates, convert to timestamp
                            if (valueA && valueA !== '0') {
                                try {
                                    valueA = new Date(valueA).getTime();
                                } catch (e) {
                                    valueA = 0;
                                }
                            } else {
                                valueA = 0;
                            }
                            
                            if (valueB && valueB !== '0') {
                                try {
                                    valueB = new Date(valueB).getTime();
                                } catch (e) {
                                    valueB = 0;
                                }
                            } else {
                                valueB = 0;
                            }
                        } else if (column === 'rating') {
                            // Ensure ratings are numeric
                            valueA = Number(valueA || 0);
                            valueB = Number(valueB || 0);
                        }
                        
                        // Handle null values - null values should sort to the end
                        if (valueA === null && valueB === null) return 0;
                        if (valueA === null) return direction === 'asc' ? 1 : -1;
                        if (valueB === null) return direction === 'asc' ? -1 : 1;
                        
                        // Compare values
                        let result;
                        if (valueA < valueB) {
                            result = -1;
                        } else if (valueA > valueB) {
                            result = 1;
                        } else {
                            result = 0;
                        }
                        
                        // Apply sort direction
                        return direction === 'asc' ? result : -result;
                    });
                    
                    // Clear and re-append rows
                    rows.forEach(row => tbody.appendChild(row));
                }
                
                // Search functionality
                const searchInput = document.getElementById('searchInput');
                searchInput.addEventListener('keyup', function() {
                    filterBooks();
                });
                
                // Filter functionality
                const statusFilter = document.getElementById('statusFilter');
                const formatFilter = document.getElementById('formatFilter');
                
                statusFilter.addEventListener('change', filterBooks);
                formatFilter.addEventListener('change', filterBooks);
                
                function filterBooks() {
                    const searchText = searchInput.value.toLowerCase();
                    const statusValue = statusFilter.value;
                    const formatValue = formatFilter.value;
                    
                    const rows = table.querySelectorAll('tbody tr');
                    let visibleCount = 0;
                    
                    rows.forEach(row => {
                        const cells = row.querySelectorAll('td');
                        
                        // Check status filter
                        const statusMatch = statusValue === 'all' || row.dataset.status === statusValue;
                        
                        // Check format filter
                        const formatMatch = formatValue === 'all' || row.dataset.format.includes(formatValue);
                        
                        // Search in text fields
                        const searchableFields = [
                            cells[1].textContent.toLowerCase(), // Title
                            cells[2].textContent.toLowerCase(), // Author
                            cells[3].textContent.toLowerCase(), // Format
                            cells[4].textContent.toLowerCase(), // Year
                            cells[5].textContent.toLowerCase(), // Date Added
                            cells[7].textContent.toLowerCase(), // Status
                        ];
                        
                        // Check if any field contains the search text
                        const searchMatch = searchText === '' || 
                            searchableFields.some(field => field.includes(searchText));
                        
                        // Combined match
                        const visible = statusMatch && formatMatch && searchMatch;
                        
                        row.style.display = visible ? '' : 'none';
                        
                        if (visible) {
                            visibleCount++;
                        }
                    });
                    
                    // Update counter
                    document.getElementById('bookCount').textContent = `Showing ${visibleCount} of ${rows.length} books`;
                }
                
                // Initial sort by title - trigger the title header click event
                const titleHeader = Array.from(headers).find(h => h.dataset.sort === 'title');
                if (titleHeader) {
                    // Set default sort to title ascending
                    currentSort = { column: 'title', direction: 'asc' };
                    titleHeader.querySelector('.sort-icon').textContent = 'expand_less';
                    sortTable('title', 'asc');
                }
            });
        </script>
        
        <style>
            /* Body and base styling for Gruvbox dark */
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
            
            /* Headers */
            h1, h2, h3, h4, h5, h6 {
                color: #fbf1c7;
            }
            
            /* Table styling for Gruvbox dark */
            #booksTable {
                border-collapse: separate;
                border-spacing: 0;
                color: #ebdbb2;
            }
            
            #booksTable th {
                border-top: none;
                color: #fbf1c7;
                font-weight: 500;
                text-transform: uppercase;
                font-size: 0.75rem;
                letter-spacing: 0.5px;
                padding: 1rem;
                border-bottom: 1px solid #504945;
            }
            
            #booksTable td {
                border-bottom: 1px solid #504945;
                padding: 1rem;
                vertical-align: middle;
            }
            
            #booksTable tr:last-child td {
                border-bottom: none;
            }
            
            #booksTable thead {
                background-color: #504945 !important;
            }
            
            /* Sortable headers */
            .sortable {
                cursor: pointer;
                user-select: none;
            }
            
            .sort-icon {
                color: #adb5bd;
            }
            
            /* Book cover and hover effect - more subtle */
            .book-cover {
                transition: all 0.25s cubic-bezier(0.2, 0.8, 0.2, 1);
                border: 1px solid #3c3836;
                filter: brightness(0.95);
            }
            
            .cover-link {
                display: block;
                position: relative;
                border-radius: 4px;
                overflow: hidden;
                width: 70px;
            }
            
            .cover-link:hover .book-cover {
                transform: scale(1.03);
                filter: brightness(1.05);
                box-shadow: 0 5px 10px rgba(0,0,0,0.15) !important;
            }
            
            /* Book title - Gruvbox dark compatible */
            .book-title {
                color: #ebdbb2;
                text-decoration: none;
                transition: color 0.2s ease;
                line-height: 1.3;
                font-family: 'Roboto', sans-serif;
                letter-spacing: 0.01em;
            }
            
            .book-title:hover {
                color: #b8bb26;
                text-decoration: none;
            }
            
            /* Author styling */
            .author-name {
                color: #a89984;
                font-size: 0.9rem;
                font-style: normal;
                font-weight: 400;
            }
            
            /* Badges - Gruvbox dark compatible */
            .format-badge {
                display: inline-block;
                background-color: #504945;
                color: #ebdbb2;
                font-size: 0.75rem;
                padding: 0.25rem 0.5rem;
                border-radius: 4px;
            }
            
            .year-badge {
                display: inline-block;
                font-size: 0.875rem;
                font-weight: 500;
                color: #d5c4a1;
            }
            
            .date-label {
                font-size: 0.813rem;
                color: #a89984;
            }
            
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
                font-size: 0.9rem;
                letter-spacing: 1px;
            }
            
            /* Progress bar */
            .progress {
                border-radius: 10px;
                background-color: #504945;
            }
            
            .progress-bar {
                background-color: #b8bb26;
            }
            
            /* Action buttons */
            .action-buttons .btn {
                padding: 0.25rem 0.5rem;
                line-height: 1;
                background-color: #504945;
                border-color: #665c54;
                color: #ebdbb2;
            }
            
            .action-buttons .btn:hover {
                background-color: #665c54;
                color: #fbf1c7;
            }
            
            .action-buttons .btn .material-icons {
                color: #ebdbb2;
            }
            
            /* Filter selects */
            .form-select-sm {
                font-size: 0.875rem;
                padding-top: 0.25rem;
                padding-bottom: 0.25rem;
                border-color: #665c54;
                background-color: #3c3836;
                color: #ebdbb2;
            }
            
            .form-select-sm:focus {
                border-color: #83a598;
                box-shadow: 0 0 0 0.25rem rgba(131, 165, 152, 0.25);
            }
            
            /* Search box */
            .input-group-text {
                color: #a89984;
                border-color: #665c54;
                background-color: #3c3836;
            }
            
            #searchInput {
                border-color: #665c54;
                background-color: #3c3836;
                color: #ebdbb2;
            }
            
            #searchInput:focus {
                box-shadow: none;
                border-color: #83a598;
            }
            
            #searchInput::placeholder {
                color: #a89984;
                opacity: 0.7;
            }
            
            /* Alternating row colors */
            #booksTable tbody tr:nth-of-type(odd) {
                background-color: #32302f;
            }
            
            #booksTable tbody tr:hover {
                background-color: rgba(146, 131, 116, 0.1);
            }
            
            /* Count indicator */
            #bookCount {
                font-size: 0.875rem;
                color: #a89984;
            }
            
            /* Text muted */
            .text-muted {
                color: #928374 !important;
            }
        </style>
    @else
        <div class="alert empty-library-alert">
            <div class="d-flex align-items-center">
                <span class="material-icons me-3">menu_book</span>
                <div>
                    <h5 class="mb-1">No books in your library yet</h5>
                    <p class="mb-0">Get started by <a href="{{ route('books.create') }}" class="alert-link">adding a book</a> or <a href="{{ route('books.import.form') }}" class="alert-link">importing from Goodreads</a>.</p>
                </div>
            </div>
        </div>
        
        <style>
            .empty-library-alert {
                background-color: #3c3836;
                color: #ebdbb2;
                border-color: #665c54;
                border-radius: 6px;
                padding: 2rem;
            }
            
            .empty-library-alert .material-icons {
                color: #83a598;
                font-size: 2rem;
            }
            
            .empty-library-alert h5 {
                color: #fbf1c7;
            }
            
            .empty-library-alert .alert-link {
                color: #8ec07c;
                text-decoration: none;
                transition: color 0.2s ease;
            }
            
            .empty-library-alert .alert-link:hover {
                color: #b8bb26;
                text-decoration: underline;
            }
        </style>
    @endif
</div>
@endsection