<?php

namespace App\Filament\Resources\ClientPerKlants\Tables;

use App\Models\ClientPerKlant;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Enums\PaginationMode;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class ClientPerKlantsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('row_number')
                    ->label('No')
                    ->getStateUsing(function ($record, $rowLoop) {
                        return $rowLoop->iteration;
                    }),
                TextColumn::make('instelling_id'),
                TextColumn::make('instelling_naam'),
                TextColumn::make('aantal_actieve_clienten'),
                TextColumn::make('aantal_inactieve_klanten'),
                TextColumn::make('recorded_month')
                    ->date('j M, Y')
                    ->sortable(),
                TextColumn::make('creator.name')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updater.name')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('aantal_actieve_clienten')
                    ->summarize(Sum::make()),
                   TextColumn::make('aantal_actieve_clienten')
                       ->label('Total Actieve Clienten')
                       ->summarize(Sum::make())
            ])
            ->paginated([500, 1000, 2000, 'all'])
            ->defaultPaginationPageOption(1000)
            ->paginationMode(PaginationMode::Cursor)
            ->filters([

//                Filter::make('recorded_month')
//                    ->schema([
//                        DatePicker::make('recorded_month')
//                            ->native(false),
//                    ]),
                SelectFilter::make('recorded_month')
                    ->options(function () {
                        $months = ClientPerKlant::query()
                            ->selectRaw('DISTINCT DATE_FORMAT(recorded_month, "%Y-%m") as month_year, recorded_month')
                            ->orderBy('recorded_month', 'desc')
                            ->pluck('month_year', 'recorded_month')
                            ->unique()
                            ->map(function ($monthYear, $date) {
                                return Carbon::parse($date)->format('F Y');
                            })
                            ->all();

                        return $months;
                    })
                    ->searchable()
                    ->native(false)
                    ->query(function (Builder $query, array $data): Builder {
                        if (!empty($data['value'])) {
                            $query->whereYear('recorded_month', explode('-', $data['value'])[0])
                                ->whereMonth('recorded_month', explode('-', $data['value'])[1]);
                        }
                        return $query;
                    }),
                TernaryFilter::make('aantal_inactieve_klanten')
                    ->native(false)
                    ->placeholder('All Klanten')
                    ->trueLabel('Active Klanten')
                    ->falseLabel('Inactive Klanten')
                    ->queries(
                        true: fn (Builder $query) => $query->where('aantal_inactieve_klanten', 0),
                        false: fn (Builder $query) => $query->where('aantal_inactieve_klanten', 1),
                        blank: fn (Builder $query) => $query,
                    )
            ], layout: FiltersLayout::AboveContent)
            ->recordActions([
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
