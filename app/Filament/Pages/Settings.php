<?php
//
//namespace App\Filament\Pages;
//
//use App\Filament\Imports\ClientPerKlantImporter;
//use Filament\Actions\Action;
//use Filament\Actions\ImportAction;
//use Filament\Pages\Page;
//use Filament\Support\Icons\Heroicon;
//
//class Settings extends Page
//{
//    protected string $view = 'filament.pages.settings';
//    protected static ?string $title = 'Client Per Klant Import';
//    protected static string|null|\BackedEnum $navigationIcon = Heroicon::OutlinedRectangleStack;
//    protected function getHeaderActions(): array
//    {
//        return [
//            ImportAction::make()
//                ->importer(ClientPerKlantImporter::class),
//        ];
//    }
//
//    public function onboardingAction(): Action
//    {
//        return ImportAction::make()
//            ->importer(ClientPerKlantImporter::class);
//    }
//}
