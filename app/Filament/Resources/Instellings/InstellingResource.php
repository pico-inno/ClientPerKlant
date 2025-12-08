<?php

namespace App\Filament\Resources\Instellings;

use App\Filament\Resources\Instellings\Pages\CreateInstelling;
use App\Filament\Resources\Instellings\Pages\EditInstelling;
use App\Filament\Resources\Instellings\Pages\ListInstellings;
use App\Filament\Resources\Instellings\Schemas\InstellingForm;
use App\Filament\Resources\Instellings\Tables\InstellingsTable;
use App\Models\Instelling;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class InstellingResource extends Resource
{
    protected static ?string $model = Instelling::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Instelling';

    public static function form(Schema $schema): Schema
    {
        return InstellingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InstellingsTable::configure($table);
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
            'index' => ListInstellings::route('/'),
            'create' => CreateInstelling::route('/create'),
            'edit' => EditInstelling::route('/{record}/edit'),
        ];
    }
}
