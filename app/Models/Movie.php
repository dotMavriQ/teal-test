<?php

namespace App\Models;

class Movie
{
    public $id;
    public $title;
    public $director;
    public $description;
    public $releaseYear;
    public $posterImage;
    public $runtime;
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