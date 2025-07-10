<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $fillable = [
        'title', 'description', 'ingredients', 'steps', 'prep_time', 'cook_time', 'servings', 'difficulty', 'category', 'tags', 'image', 'author_id', 'group_id', 'is_private', 'rating', 'rating_count'
    ];

    protected $casts = [
        'ingredients' => 'array',
        'steps' => 'array',
        'tags' => 'array',
        'is_private' => 'boolean',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
