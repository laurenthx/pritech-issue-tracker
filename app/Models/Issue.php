<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    use HasFactory;

    // Define the fillable fields for mass assignment
    protected $fillable = ['project_id', 'title', 'description', 'status', 'priority', 'due_date'];

    // Automatically cast due_date to a Carbon instance
    protected $dates = ['due_date'];

    /**
     * Get the project that owns the issue.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the comments for the issue.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the tags associated with the issue.
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class); // Eloquent assumes 'issue_tag' pivot table
    }

    /**
     * Get the users (members) assigned to this issue.
     * Defines a many-to-many relationship with the User model using the 'issue_user' pivot table.
     */
    public function members()
    {
        return $this->belongsToMany(User::class, 'issue_user');
    }
}