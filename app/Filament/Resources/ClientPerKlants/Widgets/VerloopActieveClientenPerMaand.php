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
            ->get()
            ->map(function($item) {
                $monthNames = [
                    1 => 'Jan',
                    2 => 'Feb',
                    3 => 'Mar',
                    4 => 'Apr',
                    5 => 'May',
                    6 => 'Jun',
                    7 => 'Jul',
                    8 => 'Aug',
                    9 => 'Sep',
                    10 => 'Oct',
                    11 => 'Nov',
                    12 => 'Dec'
                ];

                return [
                    'month' => $monthNames[(int)$item->month+1],
                    'year' => (int)$item->year,
                    'total' => (int)$item->total
                ];
            })
            ->toArray();
        $years = [2022, 2023];
        $columns = [TextColumn::make('month')->label('Month')];

        foreach ($years as $year) {
            $columns[] = TextColumn::make("$year")
                ->label("$year")
                ->numeric();
        }


        $result = ClientPerKlant::selectRaw('
                YEAR(recorded_month) as year,
                MONTH(recorded_month) as month,
                SUM(aantal_actieve_clienten) as total
            ')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $years = $result->pluck('year')->unique()->sort()->values();
        $monthNames = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun',
            7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'
        ];

        $formatted = collect(range(1, 12))->map(function($month) use ($monthNames, $years, $result) {
            $monthData = [
                'month' => $monthNames[$month]
            ];

            // Add each year with its value (or 0 if no data)
            foreach ($years as $year) {
                $value = $result->firstWhere('month', $month, function() use ($year) {
                    return ['year' => $year, 'total' => 0];
                });

                $monthData[$year] = $result->where('month', $month)
                    ->where('year', $year)
                    ->first()->total ?? 0;
            }

            return $monthData;
        })->toArray();

//        dd($formatted);

        return $table
            ->query(fn (): Builder => ClientPerKlant::query())
            ->records(fn (): array => [
                1 => [
                    'month' => 'Jan',
                    '2022' => '111',
                    '2023' => '222',
                ],
                2 => [
                    'month' => 'Feb',
                    '2022' => '423',
                    '2023' => '12',
                ],
                3 => [
                    'month' => 'Mar',
                    '2022' => '4123',
                    '2023' => '1423',
                ],
                4 => [
                    'month' => 'Apr',
                    '2022' => '242',
                    '2023' => '12',
                ],
                5 => [
                    'month' => 'May',
                    '2022' => '345',
                    '2023' => '634',
                ],
                6 => [
                    'month' => 'Jun',
                    '2022' => '345',
                    '2023' => '634',
                ],
                7 => [
                    'month' => 'Jul',
                    '2022' => '345',
                    '2023' => '634',
                ],
                8 => [
                    'month' => 'Aug',
                    '2022' => '412',
                    '2023' => '745',
                ],
                9 => [
                    'month' => 'Sep',
                    '2022' => '345',
                    '2023' => '634',
                ],
                10 => [
                    'month' => 'Oct',
                    '2022' => '23',
                    '2023' => '876',
                ],
                11 => [
                    'month' => 'Nov',
                    '2022' => '254',
                    '2023' => '86',
                ],
                12 => [
                    'month' => 'Dec',
                    '2022' => '523',
                    '2023' => '12',
                ],
            ])
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
