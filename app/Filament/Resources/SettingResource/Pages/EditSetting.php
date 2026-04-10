<?php

namespace App\Filament\Resources\SettingResource\Pages;

use App\Filament\Resources\SettingResource;
use Filament\Resources\Pages\EditRecord;

class EditSetting extends EditRecord
{
    protected static string $resource = SettingResource::class;

    // Ako želiš da odmah otvori record ID=1
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => 1]);
    }
}