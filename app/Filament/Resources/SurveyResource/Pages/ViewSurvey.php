<?php

namespace App\Filament\Resources\SurveyResource\Pages;

use App\Filament\Resources\SurveyResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewSurvey extends ViewRecord
{
    protected static string $resource = SurveyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
            Actions\Action::make('publishSurvey')
                ->label('Publikasikan')
                ->color('success')
                ->icon('heroicon-o-check-circle')
                ->visible(fn($record) => $record->status === 'draft')
                ->requiresConfirmation()
                ->modalHeading('Publikasikan Survey')
                ->modalDescription('Apakah Anda yakin ingin mempublikasikan survey ini? Survey yang sudah dipublikasikan akan dapat diisi oleh responden.')
                ->modalSubmitActionLabel('Ya, Publikasikan')
                ->action(function () {
                    $this->record->status = 'active';
                    $this->record->save();
                    $this->notification()->success()->title('Survey berhasil dipublikasikan')->send();
                    $this->redirect($this->getResource()::getUrl('view', ['record' => $this->record]));
                }),
            Actions\Action::make('closeSurvey')
                ->label('Tutup Survey')
                ->color('danger')
                ->icon('heroicon-o-x-circle')
                ->visible(fn($record) => $record->status === 'active')
                ->requiresConfirmation()
                ->modalHeading('Tutup Survey')
                ->modalDescription('Apakah Anda yakin ingin menutup survey ini? Responden tidak akan dapat mengisi survey yang sudah ditutup.')
                ->modalSubmitActionLabel('Ya, Tutup')
                ->action(function () {
                    $this->record->status = 'closed';
                    $this->record->save();
                    $this->notification()->success()->title('Survey berhasil ditutup')->send();
                    $this->redirect($this->getResource()::getUrl('view', ['record' => $this->record]));
                }),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return $this->record->title;
    }
}
