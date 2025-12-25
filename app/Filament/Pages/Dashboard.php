<?php

namespace App\Filament\Pages;

use App\Models\Instelling;
use App\Models\License;
use App\Models\LicenseVariant;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Filters\SelectFilter;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Builder;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;


    public function filtersForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('license_id')
                                    ->options(function () {
                                        return \App\Models\License::pluck('name', 'id');
                                    })
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(fn (callable $set) => $set('license_variant_id', null)),

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
                                    ->required(),
                            ]),
                    ])->columnSpanFull(),
            ]);
    }
}
