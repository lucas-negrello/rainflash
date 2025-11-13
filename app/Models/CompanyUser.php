<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/** @use HasFactory<\Database\Factories\CompanyUserFactory> */
class CompanyUser extends Model
{
    use HasFactory;

    protected $table = 'company_user';

    protected $fillable = [
        'company_id',
        'user_id',
        'primary_title',
        'currency',
        'active',
        'joined_at',
        'left_at',
        'meta',
    ];

    protected $casts = [
        'active' => 'boolean',
        'joined_at' => 'datetime',
        'left_at' => 'datetime',
        'meta' => 'array',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'company_user_roles', 'company_user_id', 'role_id');
    }
}
