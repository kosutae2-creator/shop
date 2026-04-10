<?php

namespace App\Filament\Resources\SettingResource\Pages;

use App\Filament\Resources\SettingResource;
use Filament\Resources\Pages\ListRecords;
use App\Models\Setting;

class ListSettings extends ListRecords
{
protected static string $resource = SettingResource::class;

}