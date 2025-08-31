<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the issues this user is assigned to.
     * Defines a many-to-many relationship with the Issue model using the 'issue_user' pivot table.
     */
    public function assignedIssues()
    {
        return $this->belongsToMany(Issue::class, 'issue_user');
    }

    /**
     * Get the projects that the user owns.
     * Defines a one-to-many relationship with the Project model.
     */
    public function projects() // <--- ADDED: Projects relationship
    {
        return $this->hasMany(Project::class);
    }
}