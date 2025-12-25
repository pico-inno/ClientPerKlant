<?php

namespace App\Filament\Pages;

use App\Filament\Resources\ClientPerKlants\Widgets\AantalActieveClientenPerMaandenJaar;
use App\Filament\Resources\ClientPerKlants\Widgets\StatsOverviewWidget;
use App\Filament\Resources\ClientPerKlants\Widgets\VerloopActieveClientenPerMaand;
use App\Models\License;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;

class ClientenDashboard extends Page
{
    use HasFiltersForm;
    public function isFullscreen(): bool
    {
        return request()->boolean('fullscreen');
    }

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


    public function getMaxContentWidth(): \Filament\Support\Enums\Width|null|string
    {
        return $this->isFullscreen() ? Width::Screen : Width::SevenExtraLarge;
    }
    protected function getLayoutData(): array
    {
        return [
            'isFullscreen' => $this->isFullscreen(),
        ];
    }


    protected string $view = 'filament.pages.clienten-dashboard';
    protected static string|null|\BackedEnum $navigationIcon = Heroicon::OutlinedRectangleStack;


    protected function getFooterWidgets(): array
    {
        return [
            AantalActieveClientenPerMaandenJaar::class,
            VerloopActieveClientenPerMaand::class,
        ];
    }
    public function getHeaderWidgetsColumns(): int | array
    {
        return [
            'sm' => 1,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('toggleFullscreen')
                ->label(fn () => $this->isFullscreen() ? 'Exit Full Screen' : 'Full Screen')
                ->icon(fn () => $this->isFullscreen()
                    ? 'heroicon-o-arrows-pointing-in'
                    : 'heroicon-o-arrows-pointing-out')
                ->url(fn () => url()->current() . ($this->isFullscreen() ? '' : '?fullscreen=1')),
        ];
    }

}
