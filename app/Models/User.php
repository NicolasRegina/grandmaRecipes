<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'avatar',
        'bio',
        'is_admin',
        'is_premium',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'is_premium' => 'boolean',
        ];
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'memberships')->withPivot('role', 'joined_at');
    }

    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    public function recipes()
    {
        return $this->hasMany(Recipe::class, 'author_id');
    }

    public function createdGroups()
    {
        return $this->hasMany(Group::class, 'creator_id');
    }

    /**
     * Grupos donde el usuario es admin del grupo (membership.role = 'admin'), esto puedo usarlo en el dashboard del perfil
     */
    public function adminGroups()
    {
        return $this->belongsToMany(Group::class, 'memberships')
            ->wherePivot('role', 'admin');
    }
}
