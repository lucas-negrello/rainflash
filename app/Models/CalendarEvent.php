<?php

namespace App\Models;

use App\Enums\CalendarEventTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** @use HasFactory<\Database\Factories\CalendarEventFactory> */
class CalendarEvent extends Model
{
    use HasFactory;

    protected $table = 'calendar_events';

    protected $fillable = [
        'calendar_id',
        'date',
        'type',
        'hours',
        'note',
        'meta',
    ];

    protected $casts = [
        'type' => CalendarEventTypeEnum::class,
        'date' => 'datetime',
        'meta' => 'array',
    ];

    public function calendar(): BelongsTo
    {
        return $this->belongsTo(Calendar::class);
    }
}
