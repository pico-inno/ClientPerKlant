<?php

namespace App\Filament\Resources\Licenses\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LicenseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('License')
                    ->schema([
                        TextInput::make('name')
                            ->columnSpanFull()
                            ->required(),
                        Repeater::make('variants')
                            ->relationship('variants')
                            ->simple(
                                TextInput::make('name')
                                    ->required(),
                            )
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),



            ]);
    }
}
