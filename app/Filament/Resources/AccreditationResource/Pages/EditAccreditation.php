<?php

namespace App\Filament\Resources\AccreditationResource\Pages;

use App\Filament\Resources\AccreditationResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions;
use Illuminate\Support\Facades\Auth;

class EditAccreditation extends EditRecord
{
    protected static string $resource = AccreditationResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['updated_by'] = Auth::id();

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
