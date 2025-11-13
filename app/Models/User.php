<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
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
        'locale',
        'timezone',
        'meta',
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
            'meta' => 'array',
        ];
    }

    public function resourceProfile(): HasOne
    {
        return $this->hasOne(ResourceProfile::class);
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'user_skills', 'user_id', 'skill_id')
            ->withPivot([
                'proficiency_level',
                'years_of_experience',
                'last_used_at',
                'meta',
            ])
            ->withTimestamps();
    }

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'company_user', 'user_id', 'company_id')
            ->withPivot([
                'primary_title',
                'currency',
                'active',
                'joined_at',
                'left_at',
                'meta',
            ])
            ->withTimestamps();
    }
}
