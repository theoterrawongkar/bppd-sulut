<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tour_places', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('sub_category_id')->constrained('sub_categories')->onDelete('cascade');
            $table->string('business_name');
            $table->string('slug')->unique();
            $table->string('owner_name');
            $table->string('owner_email');
            $table->string('phone');
            $table->string('instagram_link')->nullable();
            $table->string('facebook_link')->nullable();
            $table->text('address');
            $table->text('gmaps_link');
            $table->text('description');
            $table->decimal('ticket_price', 10, 2)->nullable();
            $table->json('facility');
            $table->enum('status', ['Menunggu Persetujuan', 'Ditolak', 'Diterima', 'Tutup Permanen'])->default('Menunggu Persetujuan');
            $table->timestamps();
        });

        Schema::create('tour_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_place_id')->constrained('tour_places')->onDelete('cascade');
            $table->string('image');
            $table->timestamps();
        });

        Schema::create('tour_operating_hours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_place_id')->constrained('tour_places')->onDelete('cascade');
            $table->enum('day', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu']);
            $table->time('open_time')->nullable();
            $table->time('close_time')->nullable();
            $table->boolean('is_open');
        });

        Schema::create('tour_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('tour_place_id')->constrained('tour_places')->onDelete('cascade');
            $table->tinyInteger('rating');
            $table->text('comment');
            $table->timestamps();

            $table->unique(['user_id', 'tour_place_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tour_places');
        Schema::dropIfExists('tour_images');
        Schema::dropIfExists('tour_operating_hours');
        Schema::dropIfExists('tour_reviews');
    }
};
