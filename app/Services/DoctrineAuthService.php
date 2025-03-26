<?php

namespace App\Services;

use Doctrine\DBAL\Connection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;

class DoctrineAuthService
{
    protected $connection;
    
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }
    
    public function findUserByEmail($email)
    {
        return $this->connection->fetchAssociative(
            'SELECT * FROM users WHERE email = ?',
            [$email]
        );
    }
    
    public function validateCredentials($user, $password)
    {
        return Hash::check($password, $user['password']);
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
        
        $this->connection->update('users', 
            ['remember_token' => $token], 
            ['id' => $user['id']]
        );
        
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