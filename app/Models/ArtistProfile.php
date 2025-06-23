<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArtistProfile extends Model
{
    /** @use HasFactory<\Database\Factories\ArtistProfileFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'stage_name',
        'owner_email',
        'phone',
        'portfolio_path',
        'field',
        'description',
        'instagram_link',
        'facebook_link',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function eventParticipants()
    {
        return $this->hasMany(EventParticipant::class);
    }
}
