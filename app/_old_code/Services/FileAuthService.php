<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;

class FileAuthService
{
    protected $userRepository;
    
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    
    public function login($email, $password, $remember = false)
    {
        $user = $this->userRepository->authenticate($email, $password);
        
        if (!$user) {
            return false;
        }
        
        $this->setUserSession($user);
        
        if ($remember) {
            $this->setRememberToken($user);
        }
        
        return true;
    }
    
    public function setUserSession($user)
    {
        Session::put('authenticated', true);
        Session::put('user_id', $user->id);
        Session::put('user_name', $user->name);
        Session::put('user_email', $user->email);
    }
    
    public function setRememberToken($user)
    {
        $token = Str::random(60);
        
        $user->rememberToken = $token;
        $this->userRepository->save((array) $user);
        
        Cookie::queue('remember_token', $token, 10080); // 7 days
    }
    
    public function logout()
    {
        Session::flush();
        Cookie::queue(Cookie::forget('remember_token'));
    }
    
    public function check()
    {
        return Session::has('authenticated') && Session::get('authenticated') === true;
    }
    
    public function user()
    {
        if (!$this->check()) {
            return null;
        }
        
        return (object) [
            'id' => Session::get('user_id'),
            'name' => Session::get('user_name'),
            'email' => Session::get('user_email'),
        ];
    }
}