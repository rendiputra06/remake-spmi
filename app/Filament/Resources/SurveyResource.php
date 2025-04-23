<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SurveyResource\Pages;
use App\Filament\Resources\SurveyResource\RelationManagers;
use App\Models\Survey;
use App\Models\Faculty;
use App\Models\Department;
use App\Models\Unit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class SurveyResource extends Resource
{
    protected static ?string $model = Survey::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationGroup = 'Penjaminan Mutu';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Utama')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Judul')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'draft' => 'Draft',
                                'active' => 'Aktif',
                                'closed' => 'Ditutup',
                            ])
                            ->default('draft')
                            ->required(),
                        Forms\Components\Select::make('visibility')
                            ->label('Visibilitas')
                            ->options([
                                'public' => 'Publik - Dapat diakses semua orang',
                                'private' => 'Privat - Hanya dapat diakses oleh pembuat',
                                'restricted' => 'Terbatas - Hanya dapat diakses pengguna tertentu',
                            ])
                            ->default('public')
                            ->required(),
                        Forms\Components\DateTimePicker::make('start_date')
                            ->label('Tanggal Mulai')
                            ->seconds(false),
                        Forms\Components\DateTimePicker::make('end_date')
                            ->label('Tanggal Berakhir')
                            ->seconds(false)
                            ->after('start_date'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Target dan Kategori')
                    ->schema([
                        Forms\Components\TextInput::make('target_audience')
                            ->label('Target Responden')
                            ->placeholder('mis: mahasiswa, dosen, alumni')
                            ->helperText('Pisahkan dengan koma untuk beberapa kelompok target')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('category')
                            ->label('Kategori')
                            ->placeholder('mis: evaluasi, kepuasan, tracer study')
                            ->maxLength(255),
                        Forms\Components\Toggle::make('is_anonymous')
                            ->label('Tanpa Nama (Anonim)')
                            ->default(true)
                            ->helperText('Jika diaktifkan, identitas responden tidak akan disimpan'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Target Unit Organisasi')
                    ->schema([
                        Forms\Components\Select::make('faculty_id')
                            ->label('Fakultas')
                            ->relationship('faculty', 'name')
                            ->options(Faculty::all()->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(fn(Forms\Set $set) => $set('department_id', null)),
                        Forms\Components\Select::make('department_id')
                            ->label('Program Studi')
                            ->options(function (Forms\Get $get) {
                                $facultyId = $get('faculty_id');
                                if (!$facultyId) {
                                    return Department::all()->pluck('name', 'id');
                                }
                                return Department::where('faculty_id', $facultyId)->pluck('name', 'id');
                            })
                            ->searchable()
                            ->preload()
                            ->visible(fn(Forms\Get $get) => $get('faculty_id') !== null),
                        Forms\Components\Select::make('unit_id')
                            ->label('Unit')
                            ->relationship('unit', 'name')
                            ->options(Unit::all()->pluck('name', 'id'))
                            ->searchable()
                            ->preload(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->sortable()
                    ->color(fn(string $state): string => match ($state) {
                        'draft' => 'gray',
                        'active' => 'success',
                        'closed' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('visibility')
                    ->label('Visibilitas')
                    ->badge()
                    ->sortable()
                    ->color(fn(string $state): string => match ($state) {
                        'public' => 'success',
                        'private' => 'danger',
                        'restricted' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Tanggal Mulai')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->label('Tanggal Berakhir')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('target_audience')
                    ->label('Target')
                    ->searchable(),
                Tables\Columns\TextColumn::make('responses_count')
                    ->label('Jumlah Respons')
                    ->counts('responses')
                    ->sortable(),
                Tables\Columns\TextColumn::make('questions_count')
                    ->label('Jumlah Pertanyaan')
                    ->counts('questions')
                    ->sortable(),
                Tables\Columns\TextColumn::make('faculty.name')
                    ->label('Fakultas')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('department.name')
                    ->label('Program Studi')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('unit.name')
                    ->label('Unit')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'active' => 'Aktif',
                        'closed' => 'Ditutup',
                    ]),
                Tables\Filters\SelectFilter::make('faculty')
                    ->label('Fakultas')
                    ->relationship('faculty', 'name'),
                Tables\Filters\SelectFilter::make('department')
                    ->label('Program Studi')
                    ->relationship('department', 'name'),
                Tables\Filters\SelectFilter::make('unit')
                    ->label('Unit')
                    ->relationship('unit', 'name'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\Action::make('analytics')
                        ->label('Lihat Analitik')
                        ->icon('heroicon-o-chart-bar')
                        ->color('warning')
                        ->url(fn(Survey $record) => route('survey-analytics.show', $record))
                        ->openUrlInNewTab()
                        ->visible(fn(Survey $record) => $record->responses()->exists()),
                    Tables\Actions\Action::make('download')
                        ->label('Download Hasil')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('success')
                        ->url(fn(Survey $record) => route('survey-analytics.export-excel', $record))
                        ->visible(fn(Survey $record) => $record->responses()->exists()),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Informasi Survey')
                    ->schema([
                        Infolists\Components\TextEntry::make('title')
                            ->label('Judul'),
                        Infolists\Components\TextEntry::make('description')
                            ->label('Deskripsi')
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'draft' => 'gray',
                                'active' => 'success',
                                'closed' => 'danger',
                            }),
                        Infolists\Components\TextEntry::make('visibility')
                            ->label('Visibilitas')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'public' => 'success',
                                'private' => 'danger',
                                'restricted' => 'warning',
                                default => 'gray',
                            }),
                        Infolists\Components\TextEntry::make('is_anonymous')
                            ->label('Anonim')
                            ->badge()
                            ->formatStateUsing(fn(bool $state): string => $state ? 'Ya' : 'Tidak')
                            ->color(fn(bool $state): string => $state ? 'info' : 'gray'),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Aksi Survei')
                    ->schema([
                        Infolists\Components\Grid::make(2)
                            ->schema([
                                // Tombol manajemen status
                                Infolists\Components\Actions::make([
                                    Infolists\Components\Actions\Action::make('publishSurvey')
                                        ->label('Publikasikan Survei')
                                        ->button()
                                        ->color('success')
                                        ->icon('heroicon-o-check-circle')
                                        ->visible(fn($record) => $record->status === 'draft')
                                        ->url(fn($record) => route('filament.admin.resources.surveys.publish', $record))
                                        ->openUrlInNewTab(),

                                    Infolists\Components\Actions\Action::make('closeSurvey')
                                        ->label('Tutup Survei')
                                        ->button()
                                        ->color('danger')
                                        ->icon('heroicon-o-x-circle')
                                        ->visible(fn($record) => $record->status === 'active')
                                        ->url(fn($record) => route('filament.admin.resources.surveys.close', $record))
                                        ->openUrlInNewTab(),
                                ])
                                    ->visible(fn($record) => $record->status === 'draft' || $record->status === 'active')
                                    ->columnSpan(1),

                                // Tombol URL Publik
                                Infolists\Components\Actions::make([
                                    Infolists\Components\Actions\Action::make('publicUrl')
                                        ->label('Buka URL Publik')
                                        ->button()
                                        ->color('primary')
                                        ->icon('heroicon-o-link')
                                        ->visible(fn($record) => $record->status === 'active' && $record->visibility === 'public')
                                        ->url(fn($record) => route('surveys.show', $record))
                                        ->openUrlInNewTab(),
                                ])
                                    ->visible(fn($record) => $record->status === 'active' && $record->visibility === 'public')
                                    ->columnSpan(1),

                                // Tombol analisis dan ekspor
                                Infolists\Components\Actions::make([
                                    Infolists\Components\Actions\Action::make('analytics')
                                        ->label('Lihat Analisis Hasil')
                                        ->button()
                                        ->color('warning')
                                        ->icon('heroicon-o-chart-bar')
                                        ->url(fn($record) => route('survey-analytics.show', $record))
                                        ->openUrlInNewTab(),
                                ])
                                    ->visible(fn($record) => $record->responses()->exists())
                                    ->columnSpan(1),

                                Infolists\Components\Actions::make([
                                    Infolists\Components\Actions\Action::make('downloadExcel')
                                        ->label('Download Excel')
                                        ->button()
                                        ->color('success')
                                        ->icon('heroicon-o-arrow-down-tray')
                                        ->url(fn($record) => route('survey-analytics.export-excel', $record))
                                        ->openUrlInNewTab(),

                                    Infolists\Components\Actions\Action::make('downloadPdf')
                                        ->label('Download PDF')
                                        ->button()
                                        ->color('danger')
                                        ->icon('heroicon-o-document-text')
                                        ->url(fn($record) => route('survey-analytics.export-pdf', $record))
                                        ->openUrlInNewTab(),
                                ])
                                    ->visible(fn($record) => $record->responses()->exists())
                                    ->columnSpan(1),
                            ]),
                    ])
                    ->visible(fn($record) => true)
                    ->collapsible(),

                Infolists\Components\Section::make('Waktu dan Target')
                    ->schema([
                        Infolists\Components\TextEntry::make('start_date')
                            ->label('Tanggal Mulai')
                            ->dateTime('d M Y H:i'),
                        Infolists\Components\TextEntry::make('end_date')
                            ->label('Tanggal Berakhir')
                            ->dateTime('d M Y H:i'),
                        Infolists\Components\TextEntry::make('target_audience')
                            ->label('Target Responden'),
                        Infolists\Components\TextEntry::make('category')
                            ->label('Kategori'),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Unit Terkait')
                    ->schema([
                        Infolists\Components\TextEntry::make('faculty.name')
                            ->label('Fakultas')
                            ->visible(fn($record) => $record->faculty_id !== null),
                        Infolists\Components\TextEntry::make('department.name')
                            ->label('Program Studi')
                            ->visible(fn($record) => $record->department_id !== null),
                        Infolists\Components\TextEntry::make('unit.name')
                            ->label('Unit')
                            ->visible(fn($record) => $record->unit_id !== null),
                    ])
                    ->columns(3),

                Infolists\Components\Section::make('Informasi Tambahan')
                    ->schema([
                        Infolists\Components\TextEntry::make('creator.name')
                            ->label('Dibuat oleh'),
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Dibuat pada')
                            ->dateTime('d M Y H:i'),
                        Infolists\Components\TextEntry::make('updater.name')
                            ->label('Diupdate oleh')
                            ->visible(fn($record) => $record->updated_by !== null),
                        Infolists\Components\TextEntry::make('updated_at')
                            ->label('Diupdate pada')
                            ->dateTime('d M Y H:i')
                            ->visible(fn($record) => $record->updated_at !== null),
                    ])
                    ->columns(2),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\QuestionsRelationManager::class,
            RelationManagers\ResponsesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSurveys::route('/'),
            'create' => Pages\CreateSurvey::route('/create'),
            'view' => Pages\ViewSurvey::route('/{record}'),
            'edit' => Pages\EditSurvey::route('/{record}/edit'),
        ];
    }
}
