<?php

namespace App\Filament\Widgets;

use App\Models\Document;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentDocuments extends BaseWidget
{
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Dokumen Terbaru')
            ->query(
                Document::query()
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->limit(40),
                Tables\Columns\TextColumn::make('category')
                    ->label('Kategori')
                    ->badge(),
                Tables\Columns\TextColumn::make('file_type')
                    ->label('Tipe')
                    ->formatStateUsing(fn(string $state): string => strtoupper(str_replace('application/', '', $state)))
                    ->badge()
                    ->color(fn(string $state): string => match (true) {
                        str_contains($state, 'pdf') => 'danger',
                        str_contains($state, 'word') => 'info',
                        str_contains($state, 'excel') || str_contains($state, 'spreadsheet') => 'success',
                        str_contains($state, 'presentation') => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('visibility')
                    ->label('Visibilitas')
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'public' => 'Publik',
                        'restricted' => 'Terbatas',
                        'private' => 'Privat',
                        default => $state,
                    })
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'public' => 'success',
                        'restricted' => 'warning',
                        'private' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('file_size')
                    ->label('Ukuran'),
                Tables\Columns\TextColumn::make('uploader.name')
                    ->label('Diunggah oleh'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->date('d M Y'),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Lihat')
                    ->icon('heroicon-m-eye')
                    ->url(fn(Document $record): string => route('filament.admin.resources.documents.view', ['record' => $record])),
                Tables\Actions\Action::make('download')
                    ->label('Unduh')
                    ->icon('heroicon-m-arrow-down-tray')
                    ->url(fn(Document $record): string => route('filament.admin.resources.documents.index'))
                    ->openUrlInNewTab(),
            ]);
    }
}
