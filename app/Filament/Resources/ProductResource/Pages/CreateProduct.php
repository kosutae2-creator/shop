<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions\Action; // Promenili smo import
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    public function getTitle(): string 
    {
        return "Dodaj novi proizvod";
    }

    public function getBreadcrumb(): string
    {
        return 'Dodavanje';
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Proizvod je uspešno kreiran!')
            ->body('Svi podaci su sačuvani u bazi.');
    }

    // IZMENA: Ovde sada piše : Action umesto : StaticAction
    protected function getCreateFormAction(): Action
    {
        return parent::getCreateFormAction()
            ->label('Sačuvaj proizvod');
    }

    // IZMENA: I ovde ide : Action
    protected function getCreateAnotherFormAction(): Action
    {
        return parent::getCreateAnotherFormAction()
            ->label('Sačuvaj i dodaj sledeći');
    }

    // IZMENA: I ovde ide : Action
    protected function getCancelFormAction(): Action
    {
        return parent::getCancelFormAction()
            ->label('Odustani');
    }
}