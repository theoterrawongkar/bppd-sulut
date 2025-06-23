<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\SubCategory;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EventPlace>
 */
class EventPlaceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $businessName = fake()->company();

        $start = fake()->dateTimeBetween('+1 days', '+1 month');
        $end = (clone $start)->modify('+2 hours');

        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'sub_category_id' => SubCategory::inRandomOrder()->first()->id,
            'business_name' => $businessName,
            'slug' => Str::slug($businessName),
            'owner_name' => fake()->name(),
            'owner_email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'instagram_link' => 'https://instagram.com/' . fake()->userName(),
            'facebook_link' => 'https://facebook.com/' . fake()->userName(),
            'address' => fake()->address(),
            'gmaps_link' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3453.6107834122745!2d124.82453850978818!3d1.4555412612224468!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3287748d30cfd679%3A0x2f0ca179d0b91656!2sUniversitas%20Sam%20Ratulangi!5e1!3m2!1sid!2sid!4v1749981828153!5m2!1sid!2sid',
            'description' => fake()->paragraph(),
            'ticket_price' => fake()->randomFloat(2, 10000, 50000),
            'start_time' => $start,
            'end_time' => $end,
        ];
    }
}
