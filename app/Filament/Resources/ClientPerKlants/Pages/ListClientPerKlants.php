<?php

namespace App\Filament\Resources\ClientPerKlants\Pages;

use App\Filament\Imports\ClientPerKlantImporter;
use App\Filament\Resources\ClientPerKlants\ClientPerKlantsResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;

class ListClientPerKlants extends ListRecords
{
    protected static string $resource = ClientPerKlantsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
                ->importer(ClientPerKlantImporter::class),
        ];
    }
}
