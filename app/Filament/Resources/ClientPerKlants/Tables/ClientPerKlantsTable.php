<?php

namespace App\Filament\Resources\ClientPerKlants\Tables;

use App\Models\ClientPerKlant;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Enums\PaginationMode;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;

class ClientPerKlantsTable
{
    public static function configure(Table $table): Table
    {
        return $table
//            ->query(ClientPerKlant::)
            ->columns([
                TextColumn::make('instelling_id'),
                TextColumn::make('instelling_naam'),
                TextColumn::make('aantal_actieve_clienten'),
                TextColumn::make('aantal_inactieve_klanten'),
                TextColumn::make('recorded_month'),
                TextColumn::make('creator.name')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updater.name')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->paginated([500, 1000, 2000, 'all'])
            ->defaultPaginationPageOption(1000)
            ->paginationMode(PaginationMode::Cursor)
            ->filters([
                Filter::make('recorded_month')
                    ->schema([
                        DatePicker::make('recorded_month')
                            ->native(false)
                            ->displayFormat('F Y'),
                    ]),

            ], layout: FiltersLayout::AboveContent)
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
