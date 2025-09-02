<?php

namespace App\Filament\Resources\ClientPerKlants\Pages;

use App\Filament\Resources\ClientPerKlants\ClientPerKlantsResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewClientPerKlants extends ViewRecord
{
    protected static string $resource = ClientPerKlantsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
