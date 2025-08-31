<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; // <--- THIS LINE IS THE FIX: Changed '->' to '\'

class Project extends Model
{
    use HasFactory;

    // Define the fillable fields for mass assignment
    protected $fillable = ['name', 'description', 'start_date', 'deadline', 'user_id'];

    // Automatically cast these attributes to Carbon instances (PHP's DateTime extension)
    // Note: Laravel 8+ automatically casts date columns added to migrations if they end in _at or _date
    // but being explicit with protected $casts is also a good practice and overrides $dates.
    // I'll update it to use $casts as it's the more modern way and covers this.
    protected $casts = [ // <--- CHANGED FROM $dates TO $casts FOR MODERN LARAVEL
        'start_date' => 'date',
        'deadline' => 'date',
    ];


    /**
     * Get the issues for the project.
     */
    public function issues()
    {
        return $this->hasMany(Issue::class);
    }

    /**
     * Get the user that owns the project.
     * This defines the inverse of the 'hasMany' relationship in the User model.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}