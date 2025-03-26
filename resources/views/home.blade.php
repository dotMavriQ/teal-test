@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="text-center mb-5">Welcome to TEAL</h1>
        </div>
    </div>
    
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
    
    <div class="row">
        <div class="col-md-4">
            <a href="#" class="feature-button reading text-decoration-none">
                <span class="material-icons">book</span>
                <h2 class="feature-title">Reading</h2>
                <p>Explore books and articles</p>
            </a>
        </div>
        
        <div class="col-md-4">
            <a href="#" class="feature-button video text-decoration-none">
                <span class="material-icons">play_circle_filled</span>
                <h2 class="feature-title">Video</h2>
                <p>Watch educational videos</p>
            </a>
        </div>
        
        <div class="col-md-4">
            <a href="#" class="feature-button games text-decoration-none">
                <span class="material-icons">sports_esports</span>
                <h2 class="feature-title">Games</h2>
                <p>Learn through interactive games</p>
            </a>
        </div>
    </div>
    
    <div class="row mt-5">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Recent Activity</div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="material-icons me-2">book</span>
                                Started reading "Introduction to Coding"
                            </div>
                            <span class="badge bg-primary rounded-pill">2 days ago</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="material-icons me-2">play_circle_filled</span>
                                Watched "Basic Algorithms"
                            </div>
                            <span class="badge bg-primary rounded-pill">1 week ago</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="material-icons me-2">sports_esports</span>
                                Completed "Code Challenge #5"
                            </div>
                            <span class="badge bg-primary rounded-pill">2 weeks ago</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Recommended for You</div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">Advanced JavaScript Concepts</h5>
                                <span class="material-icons">book</span>
                            </div>
                            <p class="mb-1">Learn about closures, prototypes, and advanced patterns</p>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">Building Modern Web Apps</h5>
                                <span class="material-icons">play_circle_filled</span>
                            </div>
                            <p class="mb-1">Video series on modern web development techniques</p>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">Algorithm Challenge</h5>
                                <span class="material-icons">sports_esports</span>
                            </div>
                            <p class="mb-1">Test your skills with this interactive coding challenge</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection