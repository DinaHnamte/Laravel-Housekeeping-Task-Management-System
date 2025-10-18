<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Image extends Model
{
    protected $fillable = [
        'uri',
        'name',
    ];

    public function Checklist(): BelongsTo
    {
        return $this->belongsTo(Checklist::class);
    }
}
