<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/** @use HasFactory<\Database\Factories\SkillFactory> */
class Skill extends Model
{
    use HasFactory;

    protected $table = 'skills';

    protected $fillable = [
        'key',
        'name',
        'category',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_skills', 'skill_id', 'user_id')
            ->withPivot([
                'proficiency_level',
                'years_of_experience',
                'last_used_at',
                'meta',
            ])
            ->withTimestamps();
    }
}
