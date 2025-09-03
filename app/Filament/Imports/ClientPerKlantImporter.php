<?php

namespace App\Filament\Imports;

use App\Models\ClientPerKlant;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Number;

class ClientPerKlantImporter extends Importer
{
    protected static ?string $model = ClientPerKlant::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('instelling_id')
                ->requiredMapping(false)
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('instelling_naam')
                ->requiredMapping(false)
                ->rules(['required', 'max:255']),
            ImportColumn::make('aantal_actieve_clienten')
                ->requiredMapping(false)
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('aantal_inactieve_klanten')
                ->requiredMapping(false)
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('recorded_month')
                ->requiredMapping(false)
                ->rules(['required', 'date']),
        ];
    }

    public function resolveRecord(): ClientPerKlant
    {
        return new ClientPerKlant();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your client per klant import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }
        Cache::forget('widget_count');
        Cache::forget('recorded_month_by_years');
        Cache::forget('all_total_aantal_actieve_clienten');
        Cache::forget('all_active_count');

        Cache::forget('table_ver_loop_clienten');
        Cache::forget('table_ver_loop_klanten');
        return $body;
    }
}
