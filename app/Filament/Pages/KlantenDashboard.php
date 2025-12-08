<?php

namespace App\Filament\Pages;

use App\Filament\Resources\ClientPerKlants\Widgets\AantalActieveKlantenPerMaandenJaar;
use App\Filament\Resources\ClientPerKlants\Widgets\VerloopActieveKlantenPerMaand;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;

class KlantenDashboard extends Page
{

    public function isFullscreen(): bool
    {
        return request()->boolean('fullscreen');
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

    protected string $view = 'filament.pages.klanten-dashboard';
    protected static string|null|\BackedEnum $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected function getHeaderWidgets(): array
    {
        return [
            AantalActieveKlantenPerMaandenJaar::class,
            VerloopActieveKlantenPerMaand::class,
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
