<?php

namespace App\Filament\Resources\ClientPerKlants\Pages;

use App\Filament\Imports\ClientPerKlantImporter;
use App\Filament\Resources\ClientPerKlants\ClientPerKlantsResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Support\Carbon;

class ListClientPerKlants extends ListRecords
{
    protected static string $resource = ClientPerKlantsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
                ->importer(ClientPerKlantImporter::class),
        ];
    }

    public function getTabs(): array
    {
        $tabs = [
            null => Tab::make('All'),
        ];

        foreach (range(1, 12) as $month) {
            $label = Carbon::create()->month($month)->format('M'); // Jan, Feb
            $key   = str_pad($month, 2, '0', STR_PAD_LEFT);        // 01–12 (safe key)

            $tabs[$key] = Tab::make($label)
                ->query(fn ($query) =>
                $query
                    ->whereMonth('recorded_month', $month)
                );
        }

        return $tabs;
    }
}
