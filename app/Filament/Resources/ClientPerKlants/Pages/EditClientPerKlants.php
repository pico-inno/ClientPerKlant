<?php

namespace App\Filament\Resources\ClientPerKlants\Pages;

use App\Filament\Resources\ClientPerKlants\ClientPerKlantsResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditClientPerKlants extends EditRecord
{
    protected static string $resource = ClientPerKlantsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
