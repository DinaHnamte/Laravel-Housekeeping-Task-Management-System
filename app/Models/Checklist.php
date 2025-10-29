<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Checklist extends Model
{
    protected $table = 'checklists';

    protected $fillable = [
        'property_id',
        'user_id',
        'assignment_id',
        'assignment_date',
        'start_time',
        'end_time',
        'status',
        'workflow_stage',
        'verified_latitude',
        'verified_longitude',
        'gps_verified_at',
        'notes',
    ];

    protected $casts = [
        'assignment_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'gps_verified_at' => 'datetime',
        'verified_latitude' => 'decimal:8',
        'verified_longitude' => 'decimal:8',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tasks(): BelongsToMany
    {
        return $this->belongsToMany(Task::class, 'checklist_tasks')
            ->withPivot('completed', 'completed_at', 'notes', 'photo', 'latitude', 'longitude')
            ->withTimestamps();
    }

    public function completedTasks()
    {
        return $this->tasks()->where('checklist_tasks.completed', true);
    }

    public function pendingTasks()
    {
        return $this->tasks()->where('checklist_tasks.completed', false);
    }

    public function roomPhotos()
    {
        return $this->hasMany(RoomPhoto::class);
    }
}
