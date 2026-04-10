<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Setting; 
use App\Models\Category; 
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;

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
        // DODAJ OVU LINIJU OVDE:
        Schema::defaultStringLength(191);

        Paginator::useTailwind();

        // 1. Deljenje podataka sa svim stranicama bez blokade licence
        View::composer('*', function ($view) {
            
            // Uzimamo postavke (ako ih nema, šaljemo prazan model da Blade ne pukne)
            $siteSettings = Schema::hasTable('settings') 
                ? Setting::first() ?? new Setting(['site_name' => 'Moj Shop']) 
                : new Setting(['site_name' => 'Moj Shop']);

            // Uzimamo kategorije za navigaciju
            $categories = collect();
            if (Schema::hasTable('categories')) {
                $categories = Category::whereNull('parent_id')
                    ->with('children')
                    ->get();
            }

            $view->with([
                'siteSettings' => $siteSettings,
                'categories' => $categories
            ]);
        });
    }
}