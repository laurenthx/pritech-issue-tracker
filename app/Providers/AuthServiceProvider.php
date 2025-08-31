<?php

namespace App\Providers;

use App\Models\Project; // <--- ADDED
use App\Policies\ProjectPolicy; // <--- ADDED

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Project::class => ProjectPolicy::class, // <--- ADDED
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Implicitly register policies
        // If your policies are located in the conventional `App\Policies` namespace,
        // Laravel can auto-discover them based on your `models` in `app/Models`.
        // Otherwise, explicitly registering them as above is required.
    }
}