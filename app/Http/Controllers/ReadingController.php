<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReadingController extends Controller
{
    public function index()
    {
        return view('reading.index');
    }
}