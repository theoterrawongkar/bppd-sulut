<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CulinaryPlace extends Model
{
    /** @use HasFactory<\Database\Factories\CulinaryPlaceFactory> */
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
        'types_of_food',
        'menu_path',
        'facility',
        'status',
    ];

    protected $casts = [
        'facility' => 'array',
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
        return $this->hasMany(CulinaryImage::class);
    }

    public function firstImage()
    {
        return $this->hasOne(CulinaryImage::class)->oldest();
    }

    public function operatingHours()
    {
        return $this->hasMany(CulinaryOperatingHour::class);
    }

    public function reviews()
    {
        return $this->hasMany(CulinaryReview::class);
    }
}
