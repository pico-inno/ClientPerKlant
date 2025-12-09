<?php

namespace App\Filament\Resources\Licenses;

use App\Filament\Resources\Licenses\Pages\CreateLicense;
use App\Filament\Resources\Licenses\Pages\EditLicense;
use App\Filament\Resources\Licenses\Pages\ListLicenses;
use App\Filament\Resources\Licenses\Pages\ViewLicense;
use App\Filament\Resources\Licenses\Schemas\LicenseForm;
use App\Filament\Resources\Licenses\Tables\LicensesTable;
use App\Models\License;
use BackedEnum;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LicenseResource extends Resource
{
    protected static ?string $model = License::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'License';

    public static function form(Schema $schema): Schema
    {
        return LicenseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LicensesTable::configure($table);
    }

    public static function infolist(Schema $infolist): Schema
    {
        return $infolist
            ->schema([

                Section::make('License Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('name')
                                    ->label('License Name')
                                    ->weight('medium'),

                                TextEntry::make('variants_count')
                                    ->label('Total Variants')
                                    ->badge()
                                    ->color(fn ($state) => $state > 0 ? 'success' : 'gray'),
                            ]),
                    ]),

                Section::make('Variants')
                    ->collapsible()
                    ->collapsed(false)
                    ->description('List of all variants under this license.')
                    ->schema([
                        RepeatableEntry::make('variants')
                            ->schema([
                                TextEntry::make('name')
                                    ->label('Variant Name')
                                    ->badge()
                                    ->color('info'),
                            ])
                            ->columnSpanFull(),
                    ]),
            ]);
    }



    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLicenses::route('/'),
            'create' => CreateLicense::route('/create'),
            'edit' => EditLicense::route('/{record}/edit'),
//            'view' => ViewLicense::route('/{record}/view'),
        ];
    }
}
