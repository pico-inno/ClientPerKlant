<?php

namespace App\Filament\Resources\ClientPerKlants\Tables;

use App\Models\ClientPerKlant;
use App\Models\Instelling;
use App\Models\License;
use App\Models\LicenseVariant;
use Carbon\Carbon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
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
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

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
            ->paginated([100, 500, 1000, 2000, 3000, 'all'])
            ->defaultPaginationPageOption(100)
            ->paginationMode(PaginationMode::Cursor)
            ->filters([
                Filter::make('recorded_year')
                    ->form([
                        Select::make('year')
                            ->label('Year')
                            ->native(false)
                            ->options(
                                collect(range(now()->year, now()->year - 10))
                                    ->mapWithKeys(fn ($year) => [$year => $year])
                            )
                            ->placeholder('Select year'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['year'] ?? null,
                            fn (Builder $query, $year) =>
                            $query->whereYear('recorded_month', $year)
                        );
                    })
                    ->indicateUsing(function (array $data): array {
                        return isset($data['year'])
                            ? ['year' => 'Year: ' . $data['year']]
                            : [];
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
                    ),
                SelectFilter::make('license_id')
                    ->label('License')
                    ->options(fn () => License::query()->pluck('name', 'id')->toArray() ?? [])
                    ->getOptionLabelUsing(fn ($value) => License::find($value)?->name)
                    ->query(function (Builder $query, array $data) {
                        if (empty($data['value'])) {
                            return $query;
                        }

                        $licenseId = $data['value'];

                        $instellingIds = Instelling::where('license_id', $licenseId)
                            ->pluck('instelling_id')
                            ->toArray();

                        return $query->whereIn('instelling_id', $instellingIds);
                    }),
                SelectFilter::make('license_variant_id')
                    ->label('License Variant')
                        ->options(fn () => LicenseVariant::query()->pluck('name', 'id')->toArray() ?? [])
                    ->getOptionLabelUsing(fn ($value) => License::find($value)?->name)

                        ->query(function (Builder $query, array $data) {

                        // no variant selected → skip
                        if (empty($data['value'])) {
                            return $query;
                        }

                        $variantId = $data['value'];

                        // extract selected license from all filter data
                        $selectedLicenseId = $data['query']['license_id'] ?? null;

                        $instellings = Instelling::query()
                            ->when($selectedLicenseId, fn ($q) =>
                            $q->where('license_id', $selectedLicenseId)
                            )
                            ->where('license_variant_id', $variantId)
                            ->pluck('instelling_id')
                            ->toArray();

                        return $query->whereIn('instelling_id', $instellings);
                    }),



            ], layout: FiltersLayout::AboveContent)
            ->recordActions([
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->after(function () {
                            Cache::flush();
                        }),
                ]),
            ]);
    }
}
