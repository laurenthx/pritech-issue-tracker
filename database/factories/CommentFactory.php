<?php

namespace Database\Factories;

use App\Models\Comment; // Import the Comment model
use App\Models\Issue;   // Import the Issue model, as Comment belongs to an Issue
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'issue_id' => Issue::factory(), // Creates an Issue if one doesn't exist
            'author_name' => $this->faker->name,
            'body' => $this->faker->paragraph(3), // A paragraph of 3 sentences
        ];
    }
}