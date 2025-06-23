<?php

namespace Database\Factories;

use App\Models\CulinaryPlace;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CulinaryOperatingHour>
 */
class CulinaryOperatingHourFactory extends Factory
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
            'day' => fake()->randomElement(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu']),
            'open_time' => '09:00',
            'close_time' => '21:00',
            'is_open' => true,
        ];
    }
}
