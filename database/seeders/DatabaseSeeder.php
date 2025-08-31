<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data (essential for fresh seeding each time for this bonus)
        Tag::query()->delete();
        Comment::query()->delete();
        Issue::query()->delete();
        Project::query()->delete();
        User::query()->delete(); // Ensure users are deleted and recreated

        // Create some test users
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'), // password is 'password'
        ]);
        User::factory(5)->create(); // Create 5 additional random users

        // Get all created users to assign them to issues later
        $users = User::all();

        // Create 5 projects
        Project::factory(5)->create()->each(function ($project) use ($users) {
            // For each project, create 3 to 7 issues
            Issue::factory(rand(3, 7))->create([
                'project_id' => $project->id,
            ])->each(function ($issue) use ($users) {
                // For each issue, create 2 to 5 comments
                Comment::factory(rand(2, 5))->create([
                    'issue_id' => $issue->id,
                ]);

                // Assign random users (members) to the issue (0 to 3 users)
                $randomUsersCount = rand(0, min(3, $users->count()));
                if ($randomUsersCount > 0) {
                    $issue->members()->attach(
                        $users->random($randomUsersCount)->pluck('id')->toArray()
                    );
                }
            });
        });

        // Create 10 tags
        $tags = Tag::factory(10)->create();

        // Attach random tags to issues
        Issue::all()->each(function ($issue) use ($tags) {
            $issue->tags()->attach(
                $tags->random(rand(0, 3))->pluck('id')->toArray()
            );
        });
    }
}