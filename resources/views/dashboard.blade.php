@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="text-center mb-5">Welcome to TEAL, {{ $user->name }}</h1>
        </div>
    </div>
    
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
    
    <div class="row">
        <div class="col-md-4">
            <a href="{{ route('reading.index') }}" class="feature-button reading text-decoration-none">
                <span class="material-icons">book</span>
                <h2 class="feature-title">Reading</h2>
                <p>Explore your book & comic collection</p>
            </a>
        </div>
        
        <div class="col-md-4">
            <a href="#" class="feature-button video text-decoration-none">
                <span class="material-icons">play_circle_filled</span>
                <h2 class="feature-title">Video</h2>
                <p>Manage your video library</p>
            </a>
        </div>
        
        <div class="col-md-4">
            <a href="#" class="feature-button games text-decoration-none">
                <span class="material-icons">sports_esports</span>
                <h2 class="feature-title">Games</h2>
                <p>Track your gaming collection</p>
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
                <div class="card-header">Collection Stats</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <div class="stat-circle reading">
                                <span class="material-icons">book</span>
                                <h4>0</h4>
                                <p>Books</p>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="stat-circle video">
                                <span class="material-icons">play_circle_filled</span>
                                <h4>0</h4>
                                <p>Videos</p>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="stat-circle games">
                                <span class="material-icons">sports_esports</span>
                                <h4>0</h4>
                                <p>Games</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .stat-circle {
        padding: 1.5rem 0;
        border-radius: 50%;
        width: 100px;
        height: 100px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }
    
    .stat-circle.reading {
        background-color: rgba(184, 187, 38, 0.2);
    }
    
    .stat-circle.reading .material-icons {
        color: #b8bb26;
    }
    
    .stat-circle.video {
        background-color: rgba(251, 73, 52, 0.2);
    }
    
    .stat-circle.video .material-icons {
        color: #fb4934;
    }
    
    .stat-circle.games {
        background-color: rgba(250, 189, 47, 0.2);
    }
    
    .stat-circle.games .material-icons {
        color: #fabd2f;
    }
    
    .stat-circle h4 {
        margin: 0;
        font-weight: bold;
    }
    
    .stat-circle p {
        margin: 0;
        font-size: 0.8rem;
    }
</style>
@endsection