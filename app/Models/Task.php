<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Task extends Model
{
    protected $fillable = [
        'property_id',
        'room_id',
        'task',
        'is_default',
        'image_id',
    ];

    public function Property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function Room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(Image::class);
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function checklists(): BelongsToMany
    {
        return $this->belongsToMany(Checklist::class, 'checklist_tasks')
            ->withTimestamps();
    }
}
