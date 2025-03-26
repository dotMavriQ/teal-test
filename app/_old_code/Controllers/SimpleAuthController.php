<?php

namespace App\Http\Controllers;

use App\Services\SimpleAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SimpleAuthController extends Controller
{
    protected $authService;
    
    public function __construct(SimpleAuthService $authService)
    {
        $this->authService = $authService;
    }
    
    public function showLoginForm()
    {
        return view('auth.simple-login');
    }
    
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');
        
        if ($this->authService->login($credentials['email'], $credentials['password'], $remember)) {
            return redirect()->intended('/dashboard');
        }
        
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email', 'remember'));
    }
    
    public function logout(Request $request)
    {
        $this->authService->logout();
        
        return redirect('/');
    }
    
    public function dashboard()
    {
        if (!$this->authService->check()) {
            return redirect('/login');
        }
        
        $user = $this->authService->user();
        
        return view('dashboard', compact('user'));
    }
}