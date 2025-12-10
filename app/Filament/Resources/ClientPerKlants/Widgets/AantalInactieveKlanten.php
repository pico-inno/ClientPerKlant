<?php

namespace App\Filament\Resources\ClientPerKlants\Widgets;

use App\Models\ClientPerKlant;
use App\Models\Instelling;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Widget;

class AantalInactieveKlanten extends StatsOverviewWidget
{
    use InteractsWithPageFilters;
    protected string $view = 'filament.resources.client-per-klants.widgets.aantal-inactieve-klanten';

    public function getStats(): array
    {
        $licenseId = $this->filters['license_id'] ?? null;
        $licenseVariantId = $this->filters['license_variant_id'] ?? null;

        $instellingIds = Instelling::query()
            ->when($licenseId, fn ($q) => $q->where('license_id', $licenseId))
            ->when($licenseVariantId, fn ($q) => $q->where('license_variant_id', $licenseVariantId))
            ->pluck('instelling_id')
            ->toArray();

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
