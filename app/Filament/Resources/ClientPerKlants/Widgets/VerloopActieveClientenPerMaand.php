<?php

namespace App\Filament\Resources\ClientPerKlants\Widgets;

use App\Models\ClientPerKlant;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class VerloopActieveClientenPerMaand extends TableWidget
{
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $result = ClientPerKlant::selectRaw('
                YEAR(recorded_month) as year,
                MONTH(recorded_month) as month,
                SUM(aantal_actieve_clienten) as total
            ')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
        $years = ClientPerKlant::select('recorded_month')
            ->distinct()
            ->get()
            ->pluck('year')
            ->filter()
            ->unique()
            ->sort()
            ->values();
//        $years = $result->pluck('year')->unique()->sort()->values();
        $monthNames = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun',
            7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'
        ];

        $formatted = collect(range(1, 12))->map(function($month) use ($monthNames, $years, $result) {
            $monthData = [
                'month' => $monthNames[$month]
            ];

            foreach ($years as $year) {
                $value = $result->firstWhere('month', $month, function() use ($year) {
                    return ['year' => $year, 'total' => 0];
                });
                $monthData[$year] = ClientPerKlant::query()
                    ->whereYear('recorded_month', $year)
                    ->whereRaw('MONTH(recorded_month) = ?', [$month])
                    ->where('aantal_inactieve_klanten', 0)
                    ->count() ?? 0;
//                $monthData[$year] = $result->where('month', $month)
//                    ->where('year', $year)
//                    ->first()->aantal_actieve_clienten ?? 0;
            }

            return $monthData;
        })->toArray();

//        dd($formatted);
        $years = [2022, 2023];
        $columns = [TextColumn::make('month')->label('Month')];

        foreach ($years as $year) {
            $columns[] = TextColumn::make($year)
                ->label("$year")
                ->numeric();
        }


//        $result = ClientPerKlant::selectRaw('
//                YEAR(recorded_month) as year,
//                MONTH(recorded_month) as month,
//                SUM(aantal_actieve_clienten) as total
//            ')
//            ->groupBy('year', 'month')
//            ->orderBy('year')
//            ->orderBy('month')
//            ->get();
//
//        $years = $result->pluck('year')->unique()->sort()->values();
//        $monthNames = [
//            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun',
//            7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'
//        ];
//
//        $formatted = collect(range(1, 12))->map(function($month) use ($monthNames, $years, $result) {
//            $monthData = [
//                'month' => $monthNames[$month]
//            ];
//
//            // Add each year with its value (or 0 if no data)
//            foreach ($years as $year) {
//                $value = $result->firstWhere('month', $month, function() use ($year) {
//                    return ['year' => $year, 'total' => 0];
//                });
//
//                $monthData[$year] = $result->where('month', $month)
//                    ->where('year', $year)
//                    ->first()->total ?? 0;
//            }
//
//            return $monthData;
//        })->toArray();

//        dd($formatted);

        return $table
            ->query(fn (): Builder => ClientPerKlant::query())
            ->records(fn (): array => $formatted)
            ->columns($columns)
//            ->columns([
//                TextColumn::make('instelling_id')
//                    ->numeric()
//                    ->sortable(),
//                TextColumn::make('instelling_naam')
//                    ->searchable(),
//                TextColumn::make('aantal_actieve_clienten')
//                    ->numeric()
//                    ->sortable(),
//                TextColumn::make('aantal_inactieve_klanten')
//                    ->numeric()
//                    ->sortable(),
//                TextColumn::make('recorded_month')
//                    ->date()
//                    ->sortable(),
//                TextColumn::make('created_by')
//                    ->numeric()
//                    ->sortable(),
//                TextColumn::make('updated_by')
//                    ->numeric()
//                    ->sortable(),
//                TextColumn::make('created_at')
//                    ->dateTime()
//                    ->sortable()
//                    ->toggleable(isToggledHiddenByDefault: true),
//                TextColumn::make('updated_at')
//                    ->dateTime()
//                    ->sortable()
//                    ->toggleable(isToggledHiddenByDefault: true),
//            ])
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
