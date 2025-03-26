<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends FileRepository
{
    public function __construct()
    {
        parent::__construct('users');
    }
    
    public function findByEmail($email)
    {
        $userData = $this->findBy('email', $email);
        
        if ($userData) {
            return new User($userData);
        }
        
        return null;
    }
    
    public function authenticate($email, $password)
    {
        $user = $this->findByEmail($email);
        
        if ($user && $user->verifyPassword($password)) {
            return $user;
        }
        
        return null;
    }
    
    public function findById($id)
    {
        $userData = $this->find($id);
        
        if ($userData) {
            return new User($userData);
        }
        
        return null;
    }
}