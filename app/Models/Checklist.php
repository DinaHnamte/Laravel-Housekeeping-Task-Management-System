<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Checklist extends Model
{
    protected $fillable = [
        'task_id',
        'property_id',
        'room_id',
        'user_id',
        'time_date_stamp_start',
        'time_date_stamp_end',
        'checked_off',
        'notes',
        'image_link',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'time_date_stamp_start' => 'datetime',
        'time_date_stamp_end' => 'datetime',
        'checked_off' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // public function Image() : HasMany {
    //     return $this->hasMany()
    // }
}
