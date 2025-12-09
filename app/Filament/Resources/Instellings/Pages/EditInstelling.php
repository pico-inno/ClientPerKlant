<?php

namespace App\Filament\Resources\Instellings\Pages;

use App\Filament\Resources\Instellings\InstellingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditInstelling extends EditRecord
{
    protected static string $resource = InstellingResource::class;

    protected function getHeaderActions(): array
    {
        return [
//            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
