@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Import Books from Goodreads</h4>
                </div>
                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('books.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-4">
                            <p>Upload your Goodreads export file in JSON format to import your books into TEAL.</p>
                            <p><strong>Note:</strong> If books with the same ISBN already exist, they will be updated.</p>
                        </div>
                        
                        <div class="mb-3">
                            <label for="goodreads_file" class="form-label">Goodreads JSON File</label>
                            <input type="file" class="form-control @error('goodreads_file') is-invalid @enderror" id="goodreads_file" name="goodreads_file" accept=".json">
                            
                            @error('goodreads_file')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="mb-3 text-center">
                            <button type="submit" class="btn btn-primary">
                                <span class="material-icons">cloud_upload</span> Import Books
                            </button>
                            <a href="{{ route('books.index') }}" class="btn btn-secondary ms-2">
                                <span class="material-icons">arrow_back</span> Back to Library
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection