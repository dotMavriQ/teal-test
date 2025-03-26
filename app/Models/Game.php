<?php

namespace App\Models;

class Game
{
    public $id;
    public $title;
    public $developer;
    public $description;
    public $releaseYear;
    public $coverImage;
    public $platform;
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
}