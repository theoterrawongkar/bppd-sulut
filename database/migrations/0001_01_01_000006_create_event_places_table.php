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
        Schema::create('event_places', function (Blueprint $table) {
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
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->timestamps();
        });

        Schema::create('event_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_place_id')->constrained('event_places')->onDelete('cascade');
            $table->string('image');
            $table->timestamps();
        });

        Schema::create('artist_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('stage_name');
            $table->string('owner_email');
            $table->string('phone');
            $table->string('portfolio_path');
            $table->string('field');
            $table->text('description');
            $table->string('instagram_link')->nullable();
            $table->string('facebook_link')->nullable();
            $table->enum('status', ['Menunggu Persetujuan', 'Ditolak', 'Diterima', 'Berhenti Permanen'])->default('Menunggu Persetujuan');
            $table->timestamps();
        });

        Schema::create('event_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('event_place_id')->constrained('event_places')->onDelete('cascade');
            $table->foreignId('artist_profile_id')->nullable()->constrained('artist_profiles')->onDelete('set null');
            $table->enum('status', ['Menunggu Persetujuan', 'Ditolak', 'Diterima', 'Berhenti Permanen'])->default('Menunggu Persetujuan');
            $table->timestamps();

            $table->unique(['user_id', 'event_place_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_places');
        Schema::dropIfExists('event_images');
        Schema::dropIfExists('artist_profiles');
        Schema::dropIfExists('event_participants');
    }
};
