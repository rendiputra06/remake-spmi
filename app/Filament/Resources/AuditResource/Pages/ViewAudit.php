<?php

namespace App\Filament\Resources\AuditResource\Pages;

use App\Filament\Resources\AuditResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAudit extends ViewRecord
{
    protected static string $resource = AuditResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
            Actions\Action::make('start_audit')
                ->label('Mulai Audit')
                ->icon('heroicon-o-play')
                ->color('primary')
                ->visible(fn() => $this->record->status === 'planned')
                ->action(function () {
                    $this->record->status = 'ongoing';
                    $this->record->save();
                    $this->notification()->success()->title('Audit dimulai')->send();
                    $this->redirect($this->getResource()::getUrl('view', ['record' => $this->record]));
                }),
            Actions\Action::make('complete_audit')
                ->label('Selesaikan Audit')
                ->icon('heroicon-o-check')
                ->color('success')
                ->visible(fn() => $this->record->status === 'ongoing')
                ->action(function () {
                    $this->record->status = 'completed';
                    $this->record->save();
                    $this->notification()->success()->title('Audit selesai')->send();
                    $this->redirect($this->getResource()::getUrl('view', ['record' => $this->record]));
                }),
        ];
    }
}
