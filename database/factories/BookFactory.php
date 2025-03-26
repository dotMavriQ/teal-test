<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Book::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(4);
        
        return [
            'title' => $title,
            'author' => fake()->name(),
            'description' => fake()->paragraph(),
            'isbn' => fake()->unique()->numerify('##########'),
            'isbn13' => fake()->unique()->numerify('#############'),
            'asin' => fake()->unique()->bothify('?#??????##'),
            'num_pages' => fake()->numberBetween(50, 1000),
            'cover_image' => 'book_stock.png',
            'publication_date' => fake()->dateTimeThisDecade(),
            'format' => fake()->randomElement(['Hardcover', 'Paperback', 'Kindle', 'Audiobook']),
            'user_rating' => fake()->numberBetween(0, 100),
            'avg_rating' => fake()->numberBetween(0, 100),
            'date_added' => fake()->dateTimeThisYear(),
            'date_started' => fake()->optional(0.7)->dateTimeThisYear(),
            'date_read' => fake()->optional(0.5)->dateTimeThisYear(),
            'owned' => fake()->boolean(70),
            'language' => fake()->randomElement(['English', 'Spanish', 'French', 'German']),
            'slug' => Str::slug($title),
        ];
    }
}