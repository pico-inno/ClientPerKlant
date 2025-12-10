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
        return $schema->schema([
            Select::make('year')
                ->label('Year')
                ->options([
                    2024 => '2024',
                    2025 => '2025',
                ]),

            Select::make('month')
                ->label('Month')
                ->options([
                    '01' => 'January',
                    '02' => 'February',
                    '03' => 'March',
                ]),

            Select::make('status')
                ->label('Status')
                ->options([
                    'active'   => 'Active',
                    'inactive' => 'Inactive',
                ]),
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


    protected function getHeaderWidgets(): array
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
