<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EventPlace extends Model
{
    /** @use HasFactory<\Database\Factories\EventPlaceFactory> */
    use HasFactory, Sluggable;

    protected $fillable = [
        'user_id',
        'sub_category_id',
        'business_name',
        'slug',
        'owner_name',
        'owner_email',
        'phone',
        'instagram_link',
        'facebook_link',
        'address',
        'gmaps_link',
        'description',
        'ticket_price',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'business_name'
            ]
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }

    public function images()
    {
        return $this->hasMany(EventImage::class);
    }

    public function firstImage()
    {
        return $this->hasOne(EventImage::class)->oldest();
    }

    public function eventParticipants()
    {
        return $this->hasMany(EventParticipant::class);
    }

    public function getStatusAttribute()
    {
        $now = Carbon::now();
        $start = Carbon::parse($this->start_time);
        $end = Carbon::parse($this->end_time);

        if ($now->lt($start)) {
            $diff = $now->diffForHumans($start, true);
            return [
                'text' => 'Dimulai dalam ' . $diff,
                'type' => 'upcoming',
            ];
        } elseif ($now->between($start, $end)) {
            return [
                'text' => 'Sedang berlangsung',
                'type' => 'ongoing',
            ];
        } else {
            return [
                'text' => 'Sudah berakhir',
                'type' => 'ended',
            ];
        }
    }
}
