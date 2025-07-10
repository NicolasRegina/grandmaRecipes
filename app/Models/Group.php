<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = [
        'name', 'description', 'image', 'creator_id', 'category', 'is_private', 'invite_code'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'memberships')->withPivot('role', 'joined_at');
    }

    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }
}
