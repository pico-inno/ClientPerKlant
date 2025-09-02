<?php

namespace App\Filament\Resources\ClientPerKlants\Widgets;

use App\Models\ClientPerKlant;
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class StatsOverviewWidget extends BaseStatsOverviewWidget
{
    use InteractsWithPageFilters;

    public function getColumns(): int | array
    {
        return 2;
    }

    protected function getStats(): array
    {
        $date = ClientPerKlant::query()->latest()->first();
//        dd($date);
        $latestYear = explode('-', $date->recorded_month)[0];
        $latestMonth = explode('-', $date->recorded_month)[1];

        $totalActieveKlanten = ClientPerKlant::whereYear('recorded_month', $latestYear)
            ->whereMonth('recorded_month', $latestMonth)
            ->where('aantal_inactieve_klanten', 0)->count();
        $totalActieveClienten = ClientPerKlant::whereYear('recorded_month', $latestYear)
            ->whereMonth('recorded_month', $latestMonth)
            ->where('aantal_inactieve_klanten', 0)->sum('aantal_actieve_clienten');
        return [
            Stat::make('Actieve Clienten',  $totalActieveClienten)
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Actieve Klanten', $totalActieveKlanten)
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger'),
        ];
    }
}
