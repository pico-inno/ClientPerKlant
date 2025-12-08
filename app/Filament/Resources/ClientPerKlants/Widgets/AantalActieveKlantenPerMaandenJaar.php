<?php

namespace App\Filament\Resources\ClientPerKlants\Widgets;

use App\Models\ClientPerKlant;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AantalActieveKlantenPerMaandenJaar extends ChartWidget
{
    protected ?string $pollingInterval = null;
    protected ?string $heading = 'Aantal Actieve Klanten Per Maanden';

    protected function getData(): array
    {
        $years = Cache::remember('recorded_month_by_years', now()->addHours(2), function () {
            return ClientPerKlant::select('recorded_month')
                ->distinct()
                ->get()
                ->pluck('year')
                ->filter()
                ->unique()
                ->sort()
                ->values();
        });

        $allData = Cache::remember('all_active_count', now()->addHours(2), function () {
            return ClientPerKlant::select([
                    DB::raw('YEAR(recorded_month) as year'),
                    DB::raw('MONTH(recorded_month) as month'),
                    DB::raw('COUNT(CASE WHEN aantal_inactieve_klanten = 0 THEN 1 END) as active_count')
                ])
                    ->groupBy(DB::raw('YEAR(recorded_month), MONTH(recorded_month)'))
                    ->orderBy('year')
                    ->orderBy('month')
                    ->toBase()
                    ->get();
        });


        $colors = ['#36A2EB', '#FF6384', '#4BC0C0', '#FF9F40', '#9966FF', '#FFCD56', '#C9CBCF'];
        $monthLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        foreach ($years as $index => $year) {
            $yearData = $allData->where('year', $year);

            $monthlyData = array_fill(0, 12, 0);

            foreach ($yearData as $data) {
                $month = (int)$data->month;
                if ($month >= 1 && $month <= 12) {
                    $monthIndex = $month - 1;
                    $monthlyData[$monthIndex] = (int)$data->active_count;
                }
            }

            $datasets[] = [
                'label' => (string)$year,
                'data' => $monthlyData,
                'backgroundColor' => $colors[$index % count($colors)],
                'borderColor' => $colors[$index % count($colors)],
                'fill' => false,
                'tension' => 0.1
            ];
        }
        return [
            'datasets' => $datasets ?? [],
            'labels' => $monthLabels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
