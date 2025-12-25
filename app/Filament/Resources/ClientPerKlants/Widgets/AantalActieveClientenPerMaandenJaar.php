<?php

namespace App\Filament\Resources\ClientPerKlants\Widgets;

use App\Models\ClientPerKlant;
use App\Models\Instelling;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AantalActieveClientenPerMaandenJaar extends ChartWidget
{
    use InteractsWithPageFilters;
    protected ?string $pollingInterval = null;
    protected ?string $heading = 'Aantal Actieve Clienten Per Maanden';
    protected ?string $maxHeight = '400px';
    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $licenseId = $this->filters['license_id'] ?? null;
        $licenseVariantId = $this->filters['license_variant_id'] ?? null;

        $instellingIds = Instelling::query()
            ->when($licenseId, fn ($q) => $q->where('license_id', $licenseId))
            ->when($licenseVariantId, fn ($q) => $q->where('license_variant_id', $licenseVariantId))
            ->pluck('instelling_id')
            ->toArray();


//        $years = Cache::remember('recorded_month_by_years', now()->addHours(2), function () {
            $years=  ClientPerKlant::select('recorded_month')
                        ->distinct()
                        ->get()
                        ->pluck('year')
                        ->filter()
                        ->unique()
                        ->sort()
                        ->values();
//        });

//        $allData = Cache::remember('all_total_aantal_actieve_clienten', now()->addHours(2), function () use ($instellingIds) {
        $allData = ClientPerKlant::select([
                DB::raw('YEAR(recorded_month) as year'),
                DB::raw('MONTH(recorded_month) as month'),
                DB::raw( 'SUM(CASE WHEN aantal_inactieve_klanten = 0 THEN aantal_actieve_clienten ELSE 0 END) as total_aantal_actieve_clienten
                ')
            ])
            ->when($instellingIds, fn ($q) => $q->whereIn('instelling_id', $instellingIds))
            ->groupBy(DB::raw('YEAR(recorded_month), MONTH(recorded_month)'))
                ->orderBy('year')
                ->orderBy('month')
                ->toBase()
                ->get();
//        });

        $colors = ['#36A2EB', '#FF6384', '#4BC0C0', '#FF9F40', '#9966FF', '#FFCD56', '#C9CBCF'];
        $monthLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        foreach ($years as $index => $year) {
            $yearData = $allData->where('year', $year);

            $monthlyData = array_fill(0, 12, 0);

            foreach ($yearData as $data) {
                $month = (int)$data->month;
                if ($month >= 1 && $month <= 12) {
                    $monthIndex = $month - 1;
                    $monthlyData[$monthIndex] = (int)$data->total_aantal_actieve_clienten;
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
