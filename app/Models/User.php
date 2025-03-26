<?php

namespace App\Models;

use Illuminate\Support\Facades\Hash;

class User
{
    public $id;
    public $name;
    public $email;
    public $password;
    public $rememberToken;
    public $createdAt;
    public $updatedAt;
    
    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
    }
    
    public function fill(array $attributes): self
    {
        foreach ($attributes as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
        
        return $this;
    }
    
    public static function findByEmail(string $email)
    {
        $users = json_decode(file_get_contents(storage_path('app/users.json')), true);
        
        foreach ($users as $userData) {
            if ($userData['email'] === $email) {
                return new self($userData);
            }
        }
        
        return null;
    }
    
    public function verifyPassword(string $password): bool
    {
        return Hash::check($password, $this->password);
    }
}