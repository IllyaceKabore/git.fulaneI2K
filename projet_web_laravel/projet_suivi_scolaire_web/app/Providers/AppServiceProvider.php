<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function boot(): void
    {
        // Permet de calculer facilement la moyenne d'un élève
        \Illuminate\Database\Eloquent\Builder::macro('withMoyenne', function () {
            return $this->withAvg('notes', 'note');
        });
        // Indique à Sanctum qu'il peut aussi authentifier les Parents
        Sanctum::usePersonalAccessTokenModel(\Laravel\Sanctum\PersonalAccessToken::class);
    }
}
