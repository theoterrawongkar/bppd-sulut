<?php

namespace Database\Factories;

use App\Models\CulinaryPlace;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CulinaryImage>
 */
class CulinaryImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'culinary_place_id' => CulinaryPlace::inRandomOrder()->first()->id,
            'image' => 'sample.jpg',
        ];
    }
}
