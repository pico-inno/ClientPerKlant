<?php

namespace App\Filament\Resources\ClientPerKlants\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ClientPerKlantsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('instelling_id')->numeric()->default(1),
                TextInput::make('instelling_naam'),
                TextInput::make('aantal_actieve_clienten')->numeric(),
                TextInput::make('aantal_inactieve_klanten')->numeric(),
                DatePicker::make('recorded_month')->default(now()),
                TextInput::make('created_by')
                ->default(1),
                TextInput::make('updated_by')
                ->default(1),
            ]);
    }
}
