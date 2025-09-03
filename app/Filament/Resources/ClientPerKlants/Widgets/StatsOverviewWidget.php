<?php

namespace App\Filament\Resources\ClientPerKlants\Widgets;

use App\Models\ClientPerKlant;
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Number;

class StatsOverviewWidget extends BaseStatsOverviewWidget
{
    protected ?string $pollingInterval = null;
    use InteractsWithPageFilters;

    public function getColumns(): int | array
    {
        return 2;
    }
    protected function getStats(): array
    {
        return Cache::remember('widget_count', now()->addHours(2), function () {
        $date = ClientPerKlant::query()->latest('recorded_month')->first();
        if ($date) {
            $latestYear = explode('-', $date->recorded_month)[0];
            $latestMonth = explode('-', $date->recorded_month)[1];

            $totalActieveKlanten = ClientPerKlant::whereYear('recorded_month', $latestYear)
                ->whereMonth('recorded_month', $latestMonth)
                ->where('aantal_inactieve_klanten', 0)->count();
            $totalActieveClienten = ClientPerKlant::whereYear('recorded_month', $latestYear)
                ->whereMonth('recorded_month', $latestMonth)
                ->where('aantal_inactieve_klanten', 0)->sum('aantal_actieve_clienten');
        }else{
            $totalActieveKlanten = 0;
            $totalActieveClienten = 0;
        }

        return [
            Stat::make('Actieve Clienten',  $totalActieveClienten)
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Actieve Klanten', $totalActieveKlanten)
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger'),
        ];
        });
    }
}
