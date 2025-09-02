<?php

namespace App\Filament\Resources\ClientPerKlants;

use App\Filament\Resources\ClientPerKlants\Pages\CreateClientPerKlants;
use App\Filament\Resources\ClientPerKlants\Pages\EditClientPerKlants;
use App\Filament\Resources\ClientPerKlants\Pages\ListClientPerKlants;
use App\Filament\Resources\ClientPerKlants\Pages\ViewClientPerKlants;
use App\Filament\Resources\ClientPerKlants\Schemas\ClientPerKlantsForm;
use App\Filament\Resources\ClientPerKlants\Schemas\ClientPerKlantsInfolist;
use App\Filament\Resources\ClientPerKlants\Tables\ClientPerKlantsTable;
use App\Models\ClientPerKlant;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ClientPerKlantsResource extends Resource
{
    protected static ?string $model = ClientPerKlant::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'ClientPerKlants';

    public static function form(Schema $schema): Schema
    {
        return ClientPerKlantsForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ClientPerKlantsInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ClientPerKlantsTable::configure($table);
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
            'index' => ListClientPerKlants::route('/'),
            'create' => CreateClientPerKlants::route('/create'),
            'view' => ViewClientPerKlants::route('/{record}'),
            'edit' => EditClientPerKlants::route('/{record}/edit'),
        ];
    }
}
