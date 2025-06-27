<?php

namespace App\Providers;

use App\Models\TourPlace;
use App\Models\CulinaryPlace;
use App\Models\EventParticipant;
use App\Policies\MyTourPlacePolicy;
use Illuminate\Support\Facades\Gate;
use App\Policies\MyCulinaryPlacePolicy;
use App\Policies\MyEventParticipation;
use App\Policies\MyEventParticipationPolicy;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(CulinaryPlace::class, MyCulinaryPlacePolicy::class);
        Gate::policy(TourPlace::class, MyTourPlacePolicy::class);
    }
}
