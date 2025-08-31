<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\Response; // Correctly imported

class ProjectPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Any authenticated user can view the list of projects
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Project $project): bool
    {
        return true; // Any authenticated user can view project details
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // Any authenticated user can create a project
    }

    /**
     * Determine whether the user can update the model.
     * Only the user who owns the project can update it.
     */
    public function update(User $user, Project $project): Response // <--- CHANGED RETURN TYPE from bool to Response
    {
        return $user->id === $project->user_id
               ? Response::allow()
               : Response::deny('You do not own this project.');
    }

    /**
     * Determine whether the user can delete the model.
     * Only the user who owns the project can delete it.
     */
    public function delete(User $user, Project $project): Response // <--- CHANGED RETURN TYPE from bool to Response
    {
        return $user->id === $project->user_id
               ? Response::allow()
               : Response::deny('You do not own this project.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Project $project): Response // <--- CHANGED RETURN TYPE for consistency
    {
        return $user->id === $project->user_id
               ? Response::allow()
               : Response::deny('You do not own this project.'); // Added deny message for consistency
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Project $project): Response // <--- CHANGED RETURN TYPE for consistency
    {
        return $user->id === $project->user_id
               ? Response::allow()
               : Response::deny('You do not own this project.'); // Added deny message for consistency
    }
}