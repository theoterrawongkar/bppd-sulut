<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventImage extends Model
{
    /** @use HasFactory<\Database\Factories\EventImageFactory> */
    use HasFactory;

    protected $fillable = [
        'event_place_id',
        'image',
    ];

    public function place()
    {
        return $this->belongsTo(EventPlace::class, 'event_place_id');
    }
}
