<?php

namespace App\Filament\Resources\AccreditationResource\Pages;

use App\Filament\Resources\AccreditationResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;

class ListAccreditations extends ListRecords
{
    protected static string $resource = AccreditationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Akreditasi'),
        ];
    }
}
