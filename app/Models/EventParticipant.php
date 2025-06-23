<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventParticipant extends Model
{
    /** @use HasFactory<\Database\Factories\EventParticipantFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_place_id',
        'artist_profile_id',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function eventPlace()
    {
        return $this->belongsTo(EventPlace::class);
    }

    public function artistProfile()
    {
        return $this->belongsTo(ArtistProfile::class);
    }
}
