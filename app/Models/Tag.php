<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    // Define the fillable fields for mass assignment
    protected $fillable = ['name', 'color'];

    /**
     * Get the issues that have this tag.
     */
    public function issues()
    {
        return $this->belongsToMany(Issue::class); // Eloquent assumes 'issue_tag' pivot table
    }
}