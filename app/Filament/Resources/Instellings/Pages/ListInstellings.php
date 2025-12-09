<?php

namespace App\Filament\Resources\Instellings\Pages;

use App\Filament\Imports\InstellingImporter;
use App\Filament\Resources\Instellings\InstellingResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;

class ListInstellings extends ListRecords
{
    protected static string $resource = InstellingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ImportAction::make()
                ->importer(InstellingImporter::class),
        ];
    }
}
