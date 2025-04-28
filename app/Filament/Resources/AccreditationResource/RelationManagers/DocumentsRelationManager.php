<?php

namespace App\Filament\Resources\AccreditationResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class DocumentsRelationManager extends RelationManager
{
    protected static string $relationship = 'documents';

    protected static ?string $recordTitleAttribute = 'title';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Judul Dokumen')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('document_number')
                    ->label('Nomor Dokumen')
                    ->maxLength(100),
                Forms\Components\Select::make('document_type')
                    ->label('Jenis Dokumen')
                    ->options([
                        'manual' => 'Manual Mutu',
                        'policy' => 'Kebijakan',
                        'procedure' => 'Prosedur',
                        'regulation' => 'Peraturan',
                        'certificate' => 'Sertifikat',
                        'report' => 'Laporan',
                        'evidence' => 'Bukti',
                        'other' => 'Lainnya',
                    ])
                    ->required(),
                Forms\Components\Select::make('standard_id')
                    ->label('Standar Terkait')
                    ->relationship('standard', 'name')
                    ->preload()
                    ->searchable(),
                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')
                    ->rows(3),
                Forms\Components\DatePicker::make('issue_date')
                    ->label('Tanggal Penerbitan')
                    ->format('Y-m-d'),
                Forms\Components\DatePicker::make('valid_until')
                    ->label('Berlaku Sampai')
                    ->format('Y-m-d'),
                Forms\Components\FileUpload::make('file_path')
                    ->label('Berkas')
                    ->disk('public')
                    ->directory('documents/accreditation')
                    ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                    ->maxSize(10240),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'review' => 'Review',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        'expired' => 'Kadaluarsa',
                        'archived' => 'Diarsipkan',
                    ])
                    ->default('draft')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('document_number')
                    ->label('Nomor Dokumen')
                    ->searchable(),
                Tables\Columns\TextColumn::make('document_type')
                    ->label('Jenis')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'manual' => 'Manual Mutu',
                        'policy' => 'Kebijakan',
                        'procedure' => 'Prosedur',
                        'regulation' => 'Peraturan',
                        'certificate' => 'Sertifikat',
                        'report' => 'Laporan',
                        'evidence' => 'Bukti',
                        'other' => 'Lainnya',
                        default => $state,
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'manual' => 'primary',
                        'policy' => 'info',
                        'procedure' => 'success',
                        'regulation' => 'warning',
                        'certificate' => 'danger',
                        'report' => 'gray',
                        'evidence' => 'secondary',
                        default => 'secondary',
                    }),
                Tables\Columns\TextColumn::make('standard.name')
                    ->label('Standar')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('issue_date')
                    ->label('Tanggal Penerbitan')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('valid_until')
                    ->label('Berlaku Sampai')
                    ->date()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'draft' => 'Draft',
                        'review' => 'Review',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        'expired' => 'Kadaluarsa',
                        'archived' => 'Diarsipkan',
                        default => $state,
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'draft' => 'gray',
                        'review' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'expired' => 'info',
                        'archived' => 'secondary',
                        default => 'secondary',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('document_type')
                    ->label('Jenis Dokumen')
                    ->options([
                        'manual' => 'Manual Mutu',
                        'policy' => 'Kebijakan',
                        'procedure' => 'Prosedur',
                        'regulation' => 'Peraturan',
                        'certificate' => 'Sertifikat',
                        'report' => 'Laporan',
                        'evidence' => 'Bukti',
                        'other' => 'Lainnya',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'review' => 'Review',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        'expired' => 'Kadaluarsa',
                        'archived' => 'Diarsipkan',
                    ]),
                Tables\Filters\SelectFilter::make('standard_id')
                    ->label('Standar')
                    ->relationship('standard', 'name'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tambah Dokumen')
                    ->mutateFormDataUsing(function (array $data) {
                        $data['created_by'] = Auth::id();
                        $data['updated_by'] = Auth::id();
                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->mutateFormDataUsing(function (array $data) {
                        $data['updated_by'] = Auth::id();
                        return $data;
                    }),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('download')
                    ->label('Unduh')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn($record) => asset('storage/' . $record->file_path))
                    ->openUrlInNewTab()
                    ->visible(fn($record) => $record->file_path !== null),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
