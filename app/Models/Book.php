<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Book extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'author',
        'description',
        'isbn',
        'isbn13',
        'asin',
        'num_pages',
        'cover_image',
        'publication_date',
        'format',
        'user_rating',
        'avg_rating',
        'date_added',
        'date_started',
        'date_read',
        'owned',
        'language',
        'slug',
    ];
    
    protected $casts = [
        'publication_date' => 'date',
        'date_added' => 'date',
        'date_started' => 'date',
        'date_read' => 'date',
        'owned' => 'boolean',
        'num_pages' => 'integer',
        'user_rating' => 'integer',
        'avg_rating' => 'integer',
    ];
    
    public function getRatingPercentage()
    {
        if (!$this->user_rating) {
            return 0;
        }
        
        return $this->user_rating;
    }
    
    public function getFormattedRating()
    {
        if (!$this->user_rating) {
            return 'Not rated';
        }
        
        $stars = round($this->user_rating / 20); // Convert percentage to 5-star scale
        return str_repeat('★', $stars) . str_repeat('☆', 5 - $stars);
    }
    
    public function getCoverImageUrl()
    {
        if (!$this->cover_image || $this->cover_image === 'default-book-cover.jpg') {
            return asset('images/book_stock.png');
        }
        
        return asset('storage/book-covers/' . $this->cover_image);
    }
    
    public function getReadStatus()
    {
        if ($this->date_read) {
            return 'Read';
        }
        
        if ($this->date_started) {
            return 'Reading';
        }
        
        return 'Unread';
    }
    
    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
    
    /**
     * Generate a new unique slug
     */
    public function generateSlug()
    {
        $slug = Str::slug($this->title);
        
        $count = static::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();
        
        return $count ? "{$slug}-{$count}" : $slug;
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($book) {
            if (empty($book->slug)) {
                $book->slug = $book->generateSlug();
            }
        });

        static::updating(function ($book) {
            // Only generate a new slug if the title changed
            if ($book->isDirty('title') && empty($book->slug)) {
                $book->slug = $book->generateSlug();
            }
        });
    }
}