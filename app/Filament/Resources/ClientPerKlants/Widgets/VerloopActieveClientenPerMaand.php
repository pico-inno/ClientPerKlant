<?php

namespace App\Filament\Resources\ClientPerKlants\Widgets;

use App\Models\ClientPerKlant;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class VerloopActieveClientenPerMaand extends TableWidget
{
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => ClientPerKlant::query())
            ->columns([
                TextColumn::make('instelling_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('instelling_naam')
                    ->searchable(),
                TextColumn::make('aantal_actieve_clienten')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('aantal_inactieve_klanten')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('recorded_month')
                    ->date()
                    ->sortable(),
                TextColumn::make('created_by')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('updated_by')
                    ->numeric()
                    ->sortable(),
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
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
