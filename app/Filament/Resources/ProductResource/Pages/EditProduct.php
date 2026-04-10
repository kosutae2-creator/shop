<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    // DODAJ OVU FUNKCIJU:
    public function getTitle(): string 
    {
        return "Izmeni proizvod";
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->label('Obriši'),
        ];
    }
    
    
    public function getBreadcrumb(): string
{
    return 'Izmeni';
}
    
}