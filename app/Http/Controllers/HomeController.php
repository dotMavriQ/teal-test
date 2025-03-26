<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Get some statistics for the dashboard
        $totalBooks = \App\Models\Book::count();
        $readBooks = \App\Models\Book::whereNotNull('date_read')->count();
        $readingBooks = \App\Models\Book::whereNotNull('date_started')->whereNull('date_read')->count();
        $recentBooks = \App\Models\Book::orderBy('created_at', 'desc')->take(5)->get();
        
        return view('dashboard', compact('totalBooks', 'readBooks', 'readingBooks', 'recentBooks'));
    }
}
