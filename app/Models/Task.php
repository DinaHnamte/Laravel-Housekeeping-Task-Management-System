<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    protected $fillable = [
        'property_id',
        'room_id',
        'task',
        'is_default',
    ];

    public function Property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function Room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}
