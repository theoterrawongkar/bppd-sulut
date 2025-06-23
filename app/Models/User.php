<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function culinaryPlaces()
    {
        return $this->hasMany(CulinaryPlace::class);
    }

    public function tourPlaces()
    {
        return $this->hasMany(TourPlace::class);
    }

    public function eventPlaces()
    {
        return $this->hasMany(EventPlace::class);
    }

    public function culinaryReviews()
    {
        return $this->hasMany(CulinaryReview::class);
    }

    public function tourReviews()
    {
        return $this->hasMany(TourReview::class);
    }

    public function artistProfile()
    {
        return $this->hasOne(ArtistProfile::class);
    }

    public function eventParticipants()
    {
        return $this->hasMany(EventParticipant::class);
    }
}
