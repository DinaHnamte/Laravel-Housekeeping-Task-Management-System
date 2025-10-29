<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomPhoto extends Model
{
    protected $fillable = [
        'checklist_id',
        'room_id',
        'photo_path',
        'original_filename',
        'latitude',
        'longitude',
        'captured_at',
        'photo_number',
    ];

    protected $casts = [
        'captured_at' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function checklist(): BelongsTo
    {
        return $this->belongsTo(Checklist::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}

