<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use App\Models\TourImage;
use App\Models\TourPlace;
use App\Models\EventImage;
use App\Models\EventPlace;
use App\Models\TourReview;
use App\Models\SubCategory;
use Illuminate\Support\Str;
use App\Models\ArtistProfile;
use App\Models\CulinaryImage;
use App\Models\CulinaryPlace;
use App\Models\CulinaryReview;
use Illuminate\Database\Seeder;
use App\Models\EventParticipant;
use App\Models\TourOperatingHour;
use App\Models\CulinaryOperatingHour;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create users with roles
        $admin = User::factory()->create([
            'name' => 'Admin BPPD',
            'email' => 'admin@bppd.com',
            'role' => 'Admin',
            'is_active' => true,
        ]);

        $culinaryUsers = User::factory(10)->create(['role' => 'Pengusaha Kuliner']);
        $tourismUsers = User::factory(10)->create(['role' => 'Pengusaha Wisata']);
        $artistUsers = User::factory(10)->create(['role' => 'Seniman']);
        $regularUsers = User::factory(69)->create(['role' => 'Pengguna']);

        $allUsers = collect([$admin])
            ->merge($culinaryUsers)
            ->merge($tourismUsers)
            ->merge($artistUsers)
            ->merge($regularUsers);

        $userIds = $allUsers->pluck('id')->toArray();

        // 2. Create categories and subcategories
        $categoryData = [
            'Kuliner' => ['Makanan Berat', 'Minuman', 'Camilan'],
            'Wisata' => ['Desa Pariwisata', 'Pulau', 'Pantai', 'Gunung', 'Taman'],
            'Event' => ['Konser', 'Pameran', 'Pertunjukan', 'Bazar']
        ];

        $subCategoriesMap = [];

        foreach ($categoryData as $categoryName => $subNames) {
            $category = Category::factory()->create([
                'name' => $categoryName,
                'slug' => Str::slug($categoryName),
            ]);

            foreach ($subNames as $subName) {
                $subCategoriesMap[$categoryName][] = SubCategory::factory()->create([
                    'category_id' => $category->id,
                    'name' => $subName,
                    'slug' => Str::slug($subName),
                ]);
            }
        }

        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        // 3. Create culinary places (for culinary users only)
        foreach ($culinaryUsers as $user) {
            $placeCount = rand(1, 3);
            for ($i = 0; $i < $placeCount; $i++) {
                $culinaryPlace = CulinaryPlace::factory()->create([
                    'user_id' => $user->id,
                    'sub_category_id' => collect($subCategoriesMap['Kuliner'])->random()->id,
                ]);

                CulinaryImage::factory(5)->create([
                    'culinary_place_id' => $culinaryPlace->id,
                ]);

                foreach ($days as $day) {
                    CulinaryOperatingHour::factory()->create([
                        'culinary_place_id' => $culinaryPlace->id,
                        'day' => $day,
                        'is_open' => true,
                    ]);
                }

                collect($userIds)->shuffle()->take(rand(1, 3))->each(function ($reviewerId) use ($culinaryPlace) {
                    CulinaryReview::factory()->create([
                        'user_id' => $reviewerId,
                        'culinary_place_id' => $culinaryPlace->id,
                    ]);
                });
            }
        }

        // 4. Create tour places (for tourism users only)
        foreach ($tourismUsers as $user) {
            $placeCount = rand(1, 3);
            for ($i = 0; $i < $placeCount; $i++) {
                $tourPlace = TourPlace::factory()->create([
                    'user_id' => $user->id,
                    'sub_category_id' => collect($subCategoriesMap['Wisata'])->random()->id,
                ]);

                TourImage::factory(5)->create([
                    'tour_place_id' => $tourPlace->id,
                ]);

                foreach ($days as $day) {
                    TourOperatingHour::factory()->create([
                        'tour_place_id' => $tourPlace->id,
                        'day' => $day,
                        'is_open' => true,
                    ]);
                }

                collect($userIds)->shuffle()->take(rand(1, 3))->each(function ($reviewerId) use ($tourPlace) {
                    TourReview::factory()->create([
                        'user_id' => $reviewerId,
                        'tour_place_id' => $tourPlace->id,
                    ]);
                });
            }
        }

        // 5. Create artist profile (only 1 per artist user)
        foreach ($artistUsers as $user) {
            ArtistProfile::factory()->create([
                'user_id' => $user->id,
            ]);
        }

        // 6. Create event places (only admin can create)
        for ($i = 0; $i < 10; $i++) {
            $eventPlace = EventPlace::factory()->create([
                'user_id' => $admin->id,
                'sub_category_id' => collect($subCategoriesMap['Event'])->random()->id,
            ]);

            EventImage::factory(5)->create([
                'event_place_id' => $eventPlace->id,
            ]);

            $artistUsers->shuffle()->take(rand(1, 3))->each(function ($artistUser) use ($eventPlace) {
                EventParticipant::factory()->create([
                    'event_place_id' => $eventPlace->id,
                    'user_id' => $artistUser->id,
                    'artist_profile_id' => $artistUser->artistProfile->id,
                ]);
            });
        }
    }
}
