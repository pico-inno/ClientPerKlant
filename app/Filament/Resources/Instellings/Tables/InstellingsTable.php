<?php

namespace App\Filament\Resources\Instellings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class InstellingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('instelling_id')
                    ->sortable()
                    ->label('Instelling ID'),

                TextColumn::make('instelling_naam')
                    ->searchable()
                    ->sortable()
                    ->label('Instelling Naam'),

                TextColumn::make('license.name')
                    ->label('License')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                TextColumn::make('variant.name')
                    ->label('Variant')
                    ->badge()
                    ->color('success')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            ->filters([
                SelectFilter::make('license_id')
                    ->relationship('license', 'name')
                    ->native(false)
                    ->label('License'),

                SelectFilter::make('license_variant_id')
                    ->relationship('variant', 'name')
                    ->native(false)
                    ->label('Variant'),

                TernaryFilter::make('is_active')
                    ->label('Active'),
            ])

            ->recordActions([
                EditAction::make(),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
