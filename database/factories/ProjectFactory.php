<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Project; // Important: Make sure to import your model

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Project::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->sentence(2), // Unique sentence of 2 words for a name
            'description' => $this->faker->paragraph,    // A paragraph of text
            'start_date' => $this->faker->date(),       // A random date
            'deadline' => $this->faker->date('Y-m-d', '+1 year'), // A date within the next year
        ];
    }
}