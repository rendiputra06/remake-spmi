<?php

namespace App\Filament\Resources\AccreditationResource\Pages;

use App\Filament\Resources\AccreditationResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateAccreditation extends CreateRecord
{
    protected static string $resource = AccreditationResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = Auth::id();
        $data['updated_by'] = Auth::id();

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
