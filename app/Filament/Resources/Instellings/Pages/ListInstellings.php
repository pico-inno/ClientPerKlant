<?php

namespace App\Filament\Resources\Instellings\Pages;

use App\Filament\Resources\Instellings\InstellingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInstellings extends ListRecords
{
    protected static string $resource = InstellingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
