<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    // Menja naslov na vrhu stranice (iznad tabele)
    public function getTitle(): string 
    {
        return "Lista proizvoda";
    }

    protected function getHeaderActions(): array
    {
        return [
            // Menja tekst na glavnom dugmetu
            Actions\CreateAction::make()
                ->label('Dodaj proizvod'),
        ];
    }
}