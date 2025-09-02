<?php

namespace App\Filament\Resources\ClientPerKlants\Widgets;

use App\Models\ClientPerKlant;
use Filament\Widgets\ChartWidget;

class AantalActieveClientenPerMaandenJaar extends ChartWidget
{
    protected ?string $heading = 'Aantal Actieve Clienten Per Maanden';

    protected function getData(): array
    {
        $years = ClientPerKlant::select('recorded_month')
            ->distinct()
            ->get()
            ->pluck('year')
            ->filter()
            ->unique()
            ->sort()
            ->values();


        $colors = ['#36A2EB', '#FF6384', '#4BC0C0', '#FF9F40', '#9966FF', '#FFCD56', '#C9CBCF'];
        $datasets = [];
        $colorIndex = 0;


        $monthLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        foreach ($years as $year) {
            $monthlyData = array_fill(0, 12, 0);

            $yearData = ClientPerKlant::whereYear('recorded_month', $year)
                ->orderBy('recorded_month')
                ->get();

            foreach ($yearData as $record) {
                $month = (int)date('n', strtotime($record->recorded_month)) - 1;

                $totalActiveValue = ClientPerKlant::query()
                    ->whereYear('recorded_month', $year)
                    ->whereRaw('MONTH(recorded_month) = ?', [$record->recorded_month->month])
                    ->where('aantal_inactieve_klanten', 0)
                    ->sum('aantal_actieve_clienten');

                $monthlyData[$month] = $totalActiveValue;
            }

            $datasets[] = [
                'label' => $year,
                'data' => $monthlyData,
                'backgroundColor' => $colors[$colorIndex % count($colors)],
                'borderColor' => $colors[$colorIndex % count($colors)],
                'fill' => false,
                'tension' => 0.1
            ];
            $colorIndex++;
        }

        return [
            'datasets' => $datasets,
            'labels' => $monthLabels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
