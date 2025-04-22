<?php

namespace App\Filament\Resources\DocumentResource\Pages;

use App\Filament\Resources\DocumentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CreateDocument extends CreateRecord
{
    protected static string $resource = DocumentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['uploaded_by'] = Auth::id();
        $data['updated_by'] = Auth::id();

        if (isset($data['file_path'])) {
            $fileName = pathinfo($data['file_path'], PATHINFO_BASENAME);
            $fileType = Storage::mimeType($data['file_path']);
            $fileSize = Storage::size($data['file_path']);

            $data['file_name'] = $fileName;
            $data['file_type'] = $fileType;
            $data['file_size'] = $this->formatBytes($fileSize);
        }

        return $data;
    }

    private function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
