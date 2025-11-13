<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/** @use HasFactory<\Database\Factories\TeamFactory> */
class Team extends Model
{
    use HasFactory;

    protected $table = 'teams';

    protected $fillable = [
        'company_id',
        'name',
        'description',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function teamMembers(): HasMany
    {
        return $this->hasMany(TeamMember::class, 'team_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function companyUsers(): BelongsToMany
    {
        return $this->belongsToMany(CompanyUser::class, 'team_members', 'team_id', 'company_user_id');
    }
}
