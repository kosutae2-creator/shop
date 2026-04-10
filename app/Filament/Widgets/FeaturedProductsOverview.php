<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class FeaturedProductsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Istaknuti Proizvodi', Product::where('is_featured', true)->count())
                ->description('Broj proizvoda na početnoj strani')
                ->descriptionIcon('heroicon-m-bolt')
                ->color('success'),
            
            Stat::make('Ukupno Proizvoda', Product::count())
                ->descriptionIcon('heroicon-m-shopping-bag'),
        ];
    }
}