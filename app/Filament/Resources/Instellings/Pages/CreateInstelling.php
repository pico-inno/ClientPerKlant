<?php

namespace App\Filament\Resources\Instellings\Pages;

use App\Filament\Resources\Instellings\InstellingResource;
use Filament\Resources\Pages\CreateRecord;

class CreateInstelling extends CreateRecord
{
    protected static string $resource = InstellingResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
