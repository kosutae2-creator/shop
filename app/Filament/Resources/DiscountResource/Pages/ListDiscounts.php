<?php

namespace App\Filament\Resources\DiscountResource\Pages;

use App\Filament\Resources\DiscountResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDiscounts extends ListRecords
{
    protected static string $resource = DiscountResource::class;

    // Menja naslov na vrhu stranice
    public function getTitle(): string 
    {
        return "Lista popusta";
    }

    protected function getHeaderActions(): array
    {
        return [
            // Ovde menjamo "New popust" u "Dodaj popust"
            Actions\CreateAction::make()
                ->label('Dodaj popust'),
        ];
    }
}