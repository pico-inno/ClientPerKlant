<?php

namespace App\Filament\Resources\Instellings\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class InstellingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Instelling Information')
                    ->schema([
                        TextInput::make('instelling_id')
                            ->numeric()
                            ->required()
                            ->columnSpanFull(),

                        TextInput::make('instelling_naam')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Select::make('license_id')
                            ->relationship('license', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn (callable $set) => $set('license_variant_id', null))
                            ->columnSpanFull(),

                        Select::make('license_variant_id')
                            ->label('License Variant')
                            ->options(function (callable $get) {
                                $licenseId = $get('license_id');

                                if (! $licenseId) {
                                    return [];
                                }

                                return \App\Models\LicenseVariant::where('license_id', $licenseId)
                                    ->pluck('name', 'id');
                            })
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpanFull(),


                        Toggle::make('is_active')
                            ->default(true)
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
