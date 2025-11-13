<?php

namespace App\Models;

use App\Enums\ResourceProfileSeniorityEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** @use HasFactory<\Database\Factories\ResourceProfileFactory> */
class ResourceProfile extends Model
{
    use HasFactory;

    protected $table = 'resource_profiles';

    protected $fillable = [
        'user_id',
        'seniority',
        'headline',
        'bio',
        'location',
        'attachments',
        'meta',
    ];

    protected $casts = [
        'seniority' => ResourceProfileSeniorityEnum::class,
        'attachments' => 'array',
        'meta' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
