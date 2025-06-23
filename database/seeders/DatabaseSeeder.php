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
        // 1. Buat 10 user, user pertama adalah admin
        $users = User::factory(100)->make();
        $users[0]->name = 'Admin BPPD';
        $users[0]->email = 'admin@bppd.com';
        $users[0]->role = 'Admin';
        $users[0]->is_active = true;
        $users->each->save();

        $userIds = User::pluck('id')->toArray();

        // 2. Buat kategori dan subkategori
        $categoryData = [
            'Kuliner' => ['Makanan Berat', 'Minuman', 'Camilan'],
            'Wisata' => ['Desa Pariwisata', 'Pulau', 'Pantai', 'Gunung', 'Taman'],
            'Event' => ['Konser', 'Pameran', 'Pertunjukan', 'Bazar']
        ];

        $subCategoriesMap = [];

        foreach ($categoryData as $cat => $subs) {
            $category = Category::factory()->create([
                'name' => $cat,
                'slug' => Str::slug($cat)
            ]);

            foreach ($subs as $sub) {
                $subCategoriesMap[$cat][] = SubCategory::factory()->create([
                    'category_id' => $category->id,
                    'name' => $sub,
                    'slug' => Str::slug($sub)
                ]);
            }
        }

        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        $usedUserIds = [];

        // 3. CulinaryPlaces
        $kulinerUsers = collect($userIds)->diff($usedUserIds)->shuffle()->take(10);
        foreach ($kulinerUsers as $userId) {
            $place = CulinaryPlace::factory()->create([
                'user_id' => $userId,
                'sub_category_id' => collect($subCategoriesMap['Kuliner'])->random()->id,
            ]);
            $usedUserIds[] = $userId;

            CulinaryImage::factory(5)->create(['culinary_place_id' => $place->id]);

            foreach ($days as $day) {
                CulinaryOperatingHour::factory()->create([
                    'culinary_place_id' => $place->id,
                    'day' => $day,
                    'is_open' => true
                ]);
            }

            collect($userIds)->shuffle()->take(rand(1, 3))->each(function ($reviewerId) use ($place) {
                CulinaryReview::factory()->create([
                    'user_id' => $reviewerId,
                    'culinary_place_id' => $place->id,
                ]);
            });
        }

        // 4. TourPlaces
        $wisataUsers = collect($userIds)->diff($usedUserIds)->shuffle()->take(10);
        foreach ($wisataUsers as $userId) {
            $place = TourPlace::factory()->create([
                'user_id' => $userId,
                'sub_category_id' => collect($subCategoriesMap['Wisata'])->random()->id,
            ]);
            $usedUserIds[] = $userId;

            TourImage::factory(5)->create(['tour_place_id' => $place->id]);

            foreach ($days as $day) {
                TourOperatingHour::factory()->create([
                    'tour_place_id' => $place->id,
                    'day' => $day,
                    'is_open' => true
                ]);
            }

            collect($userIds)->shuffle()->take(rand(1, 3))->each(function ($reviewerId) use ($place) {
                TourReview::factory()->create([
                    'user_id' => $reviewerId,
                    'tour_place_id' => $place->id,
                ]);
            });
        }

        // 5. Seniman + ArtistProfile
        $senimanUsers = collect($userIds)->diff($usedUserIds)->shuffle()->take(10);
        foreach ($senimanUsers as $userId) {
            $user = User::find($userId);
            $user->update(['role' => 'Seniman']);
            $usedUserIds[] = $userId;

            ArtistProfile::factory()->create(['user_id' => $userId]);
        }

        // 6. EventPlaces
        $eventUsers = collect($userIds)->diff($usedUserIds)->shuffle()->take(10);
        foreach ($eventUsers as $userId) {
            $place = EventPlace::factory()->create([
                'user_id' => $userId,
                'sub_category_id' => collect($subCategoriesMap['Event'])->random()->id,
            ]);
            $usedUserIds[] = $userId;

            EventImage::factory(5)->create(['event_place_id' => $place->id]);

            // Tambahkan seniman acak sebagai peserta
            $senimanList = User::where('role', 'Seniman')->get();
            $senimanList->shuffle()->take(rand(1, 3))->each(function ($seniman) use ($place) {
                EventParticipant::factory()->create([
                    'event_place_id' => $place->id,
                    'user_id' => $seniman->id,
                    'artist_profile_id' => $seniman->artistProfile->id ?? null,
                ]);
            });
        }
    }
}
