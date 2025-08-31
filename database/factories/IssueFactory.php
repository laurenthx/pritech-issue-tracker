<?php

namespace Database\Factories;

use App\Models\Issue;   // Import the Issue model
use App\Models\Project; // Import the Project model, as Issue belongs to a Project
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Issue>
 */
class IssueFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Issue::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['open', 'in_progress', 'closed'];
        $priorities = ['low', 'medium', 'high'];

        return [
            'project_id' => Project::factory(), // Creates a Project if one doesn't exist
            'title' => $this->faker->sentence(5),
            'description' => $this->faker->paragraph,
            'status' => $this->faker->randomElement($statuses), // Random status from the enum
            'priority' => $this->faker->randomElement($priorities), // Random priority from the enum
            'due_date' => $this->faker->optional()->date(), // Optional date (can be null)
        ];
    }
}