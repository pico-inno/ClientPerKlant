<?php

namespace App\Filament\Resources\ClientPerKlants\Widgets;

use App\Models\ClientPerKlant;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Widget;

class AantalInactieveKlanten extends StatsOverviewWidget
{
    protected string $view = 'filament.resources.client-per-klants.widgets.aantal-inactieve-klanten';

    public function getStats(): array
    {
        return [
            Stat::make(
                label: 'Total posts',
                value: ClientPerKlant::query()
                    ->where('aantal_inactieve_klanten', 1)
                    ->count(),
            ),
        ];
    }
}
