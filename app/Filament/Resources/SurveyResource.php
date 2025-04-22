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
                    Tables\Actions\Action::make('download')
                        ->label('Download Hasil')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('success')
                        ->url(fn(Survey $record) => route('filament.admin.resources.surveys.index'))
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
                        Infolists\Components\TextEntry::make('is_anonymous')
                            ->label('Anonim')
                            ->badge()
                            ->formatStateUsing(fn(bool $state): string => $state ? 'Ya' : 'Tidak')
                            ->color(fn(bool $state): string => $state ? 'info' : 'gray'),
                    ])
                    ->columns(2),

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
