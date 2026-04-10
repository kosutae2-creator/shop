<?php

namespace App\Filament\Resources\DiscountResource\Pages;

use App\Filament\Resources\DiscountResource;
use Filament\Actions;
use Filament\Actions\Action; // Importujemo ispravan Action
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditDiscount extends EditRecord
{
    protected static string $resource = DiscountResource::class;

    // Menja naslov stranice
    public function getTitle(): string 
    {
        return "Izmeni popust";
    }

    // Menja naslov u navigaciji (Breadcrumbs)
    public function getBreadcrumb(): string
    {
        return 'Izmena';
    }

    // Poruka nakon uspešnog čuvanja izmena
    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Izmene su sačuvane!')
            ->body('Popust je uspešno ažuriran.');
    }

    protected function getHeaderActions(): array
    {
        return [
            // Prevod dugmeta za brisanje u vrhu stranice
            Actions\DeleteAction::make()
                ->label('Obriši popust'),
        ];
    }

    // Prevod glavnog dugmeta "Save changes"
    protected function getSaveFormAction(): Action
    {
        return parent::getSaveFormAction()
            ->label('Sačuvaj izmene');
    }

    // Prevod dugmeta "Cancel"
    protected function getCancelFormAction(): Action
    {
        return parent::getCancelFormAction()
            ->label('Odustani');
    }
}