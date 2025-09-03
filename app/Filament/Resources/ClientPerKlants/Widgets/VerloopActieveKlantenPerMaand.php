<?php

namespace App\Filament\Resources\ClientPerKlants\Widgets;

use App\Models\ClientPerKlant;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

class VerloopActieveKlantenPerMaand extends TableWidget
{
    protected ?string $pollingInterval = null;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
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

        $formatted = Cache::remember('table_ver_loop_klanten', now()->addHours(2), function () use ($years) {
        $result = ClientPerKlant::selectRaw('
                YEAR(recorded_month) as year,
                MONTH(recorded_month) as month,
                SUM(aantal_actieve_clienten) as total
            ')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $monthNames = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun',
            7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'
        ];

        return collect(range(1, 12))->map(function($month) use ($monthNames, $years, $result) {
            $monthData = [
                'month' => $monthNames[$month]
            ];

            foreach ($years as $year) {

                $monthData[$year] = ClientPerKlant::query()
                    ->whereYear('recorded_month', $year)
                    ->whereRaw('MONTH(recorded_month) = ?', [$month])
                    ->where('aantal_inactieve_klanten', 0)
                    ->count() ?? 0;
            }

            return $monthData;
        })->toArray();
        });

        $columns = [TextColumn::make('month')->label('Month')];

        foreach ($years as $year) {
            $columns[] = TextColumn::make($year)
                ->label("$year")
                ->numeric();
        }

        return $table
            ->query(fn (): Builder => ClientPerKlant::query())
            ->records(fn (): array => $formatted)
            ->columns($columns)
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
