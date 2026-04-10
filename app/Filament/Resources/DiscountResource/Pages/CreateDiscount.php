<?php

namespace App\Filament\Resources\DiscountResource\Pages;

use App\Filament\Resources\DiscountResource;
use Filament\Actions\Action; // Važno: Koristimo Action umesto StaticAction
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateDiscount extends CreateRecord
{
    protected static string $resource = DiscountResource::class;

    // Menja naslov stranice na vrhu
    public function getTitle(): string 
    {
        return "Dodaj novi popust";
    }

    // Menja naslov u putokazu (Breadcrumbs)
    public function getBreadcrumb(): string
    {
        return 'Kreiranje';
    }

    // Poruka uspeha na srpskom
    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Popust kreiran!')
            ->body('Novi popust je uspešno dodat u sistem.');
    }

    // Prevod glavnog dugmeta "Create"
    protected function getCreateFormAction(): Action
    {
        return parent::getCreateFormAction()
            ->label('Sačuvaj popust');
    }

    // Prevod dugmeta "Create & Create Another"
    protected function getCreateAnotherFormAction(): Action
    {
        return parent::getCreateAnotherFormAction()
            ->label('Sačuvaj i dodaj sledeći');
    }

    // Prevod dugmeta "Cancel"
    protected function getCancelFormAction(): Action
    {
        return parent::getCancelFormAction()
            ->label('Odustani');
    }
}