<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class SimpleAuthService
{
    protected $usersFile;
    
    public function __construct()
    {
        $this->usersFile = storage_path('app/users.json');
        $this->ensureUsersFileExists();
    }
    
    protected function ensureUsersFileExists()
    {
        if (!file_exists($this->usersFile)) {
            $defaultUsers = [
                [
                    'id' => 1,
                    'name' => 'Admin',
                    'email' => 'dotmavriq@dotmavriq.life',
                    'password' => password_hash('TEALAdmin@2025#Secure', PASSWORD_BCRYPT),
                    'remember_token' => null,
                ]
            ];
            
            file_put_contents($this->usersFile, json_encode($defaultUsers, JSON_PRETTY_PRINT));
        }
    }
    
    public function getUsers()
    {
        return json_decode(file_get_contents($this->usersFile), true);
    }
    
    public function findUserByEmail($email)
    {
        $users = $this->getUsers();
        
        foreach ($users as $user) {
            if ($user['email'] === $email) {
                return $user;
            }
        }
        
        return null;
    }
    
    public function validateCredentials($user, $password)
    {
        return password_verify($password, $user['password']);
    }
    
    public function login($email, $password, $remember = false)
    {
        $user = $this->findUserByEmail($email);
        
        if (!$user) {
            return false;
        }
        
        if (!$this->validateCredentials($user, $password)) {
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
        Session::put('user_id', $user['id']);
        Session::put('user_name', $user['name']);
        Session::put('user_email', $user['email']);
    }
    
    public function setRememberToken($user)
    {
        $token = Str::random(60);
        $users = $this->getUsers();
        
        foreach ($users as &$u) {
            if ($u['id'] === $user['id']) {
                $u['remember_token'] = $token;
                break;
            }
        }
        
        file_put_contents($this->usersFile, json_encode($users, JSON_PRETTY_PRINT));
        
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