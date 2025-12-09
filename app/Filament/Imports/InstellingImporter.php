<?php

namespace App\Filament\Imports;

use App\Models\Instelling;
use App\Models\License;
use App\Models\LicenseVariant;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;
use Illuminate\Validation\ValidationException;

class InstellingImporter extends Importer
{
    protected static ?string $model = Instelling::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('instelling_id')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('instelling_naam')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('license_variant_name')
                ->label('License Variant Name')
                ->rules(['required', 'max:255'])
                ->fillRecordUsing(function ($record, $state) {
                    $variant = LicenseVariant::where('name', $state)->first();

                    if (! $variant) {
                        throw ValidationException::withMessages([
                            'license_variant_name' => "License variant '{$state}' not found.",
                        ]);
                    }

                    $record->license_variant_id = $variant->id;
                }),
            ImportColumn::make('license_name')
                ->label('License Name')
                ->rules(['required', 'max:255'])
                ->fillRecordUsing(function ($record, $state) {
                    $license = License::where('name', $state)->first();

                    if (! $license) {
                        throw ValidationException::withMessages([
                            'license_name' => "License name '{$state}' not found in system.",
                        ]);
                    }

                    $record->license_id = $license->id;
                }),
            ImportColumn::make('is_active')
                ->requiredMapping()
                ->boolean()
                ->rules(['required', 'boolean']),
        ];
    }

//    protected function beforeFill(): void
//    {
//        $variant = LicenseVariant::where('name', $this->data['license_variant_name'])->first();
//        $this->data['license_variant_id'] = $variant?->id;
//
//        $license = License::where('name', $this->data['license_name'])->first();
//        $this->data['license_id'] = $license?->id;
//    }

    public function resolveRecord(): ?Instelling
    {
        $incomingId = $this->data['instelling_id'] ?? null;

        if (blank($incomingId)) {
            return new Instelling();
        }

        $incomingId = is_numeric($incomingId) ? (int) $incomingId : $incomingId;

        $existing = Instelling::where('instelling_id', $incomingId)->first();

        if ($existing) {
            \Log::info("Import skipped: instelling_id {$incomingId} already exists.");
            return null;
        }

        return new Instelling();
    }


    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your instelling import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
