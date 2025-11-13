<?php

namespace App\Models;

use App\Enums\UserSkillProficiencyLevelEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSkill extends Model
{
    use HasFactory;

    protected $table = 'user_skills';

    protected $fillable = [
        'user_id',
        'skill_id',
        'proficiency_level',
        'years_of_experience',
        'last_used_at',
        'meta',
    ];

    protected $casts = [
        'proficiency_level' => UserSkillProficiencyLevelEnum::class,
        'last_used_at' => 'datetime',
        'meta' => 'array',
    ];

    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class, 'skill_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
