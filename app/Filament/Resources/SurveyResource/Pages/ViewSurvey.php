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
            Actions\Action::make('publicUrl')
                ->label('URL Publik')
                ->color('primary')
                ->icon('heroicon-o-link')
                ->visible(fn($record) => $record->status === 'active' && $record->visibility === 'public')
                ->url(fn($record) => route('surveys.show', $record))
                ->openUrlInNewTab(),
            Actions\Action::make('analytics')
                ->label('Analisis Hasil')
                ->color('warning')
                ->icon('heroicon-o-chart-bar')
                ->visible(fn($record) => $record->responses()->exists())
                ->url(fn($record) => route('survey-analytics.show', $record))
                ->openUrlInNewTab(),
            Actions\Action::make('downloadExcel')
                ->label('Download Excel')
                ->color('success')
                ->icon('heroicon-o-arrow-down-tray')
                ->visible(fn($record) => $record->responses()->exists())
                ->url(fn($record) => route('survey-analytics.export-excel', $record))
                ->openUrlInNewTab(),
            Actions\Action::make('downloadPdf')
                ->label('Download PDF')
                ->color('danger')
                ->icon('heroicon-o-document-text')
                ->visible(fn($record) => $record->responses()->exists())
                ->url(fn($record) => route('survey-analytics.export-pdf', $record))
                ->openUrlInNewTab(),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return $this->record->title;
    }
}
