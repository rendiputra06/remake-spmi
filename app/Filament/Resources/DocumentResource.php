<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DocumentResource\Pages;
use App\Filament\Resources\DocumentResource\RelationManagers;
use App\Models\Department;
use App\Models\Document;
use App\Models\Faculty;
use App\Models\Standard;
use App\Models\Unit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Manajemen SPMI';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Dokumen')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Judul Dokumen')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('category')
                            ->label('Kategori')
                            ->options([
                                'SOP' => 'Standar Operasional Prosedur',
                                'Pedoman' => 'Pedoman',
                                'Kebijakan' => 'Kebijakan',
                                'Laporan' => 'Laporan',
                                'Instruksi Kerja' => 'Instruksi Kerja',
                                'Formulir' => 'Formulir',
                                'Lainnya' => 'Lainnya',
                            ])
                            ->required(),
                        Forms\Components\Select::make('visibility')
                            ->label('Visibilitas')
                            ->options([
                                'public' => 'Publik - Dapat diakses semua orang',
                                'private' => 'Privat - Hanya dapat diakses oleh pembuat',
                                'restricted' => 'Terbatas - Hanya dapat diakses oleh unit/prodi/fakultas terkait',
                            ])
                            ->default('private')
                            ->required(),
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('file_path')
                            ->label('Upload Dokumen')
                            ->required()
                            ->directory('documents')
                            ->visibility('private')
                            ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'text/plain', 'image/jpeg', 'image/png'])
                            ->maxSize(5120) // 5MB
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Keterkaitan')
                    ->schema([
                        Forms\Components\Select::make('standard_id')
                            ->label('Standar Terkait')
                            ->relationship('standard', 'name')
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('faculty_id')
                            ->label('Fakultas Terkait')
                            ->relationship('faculty', 'name')
                            ->searchable()
                            ->preload()
                            ->live(),
                        Forms\Components\Select::make('department_id')
                            ->label('Prodi Terkait')
                            ->relationship(
                                'department',
                                'name',
                                fn(Builder $query, $get) =>
                                $query->when(
                                    $get('faculty_id'),
                                    fn($query, $facultyId) =>
                                    $query->where('faculty_id', $facultyId)
                                )
                            )
                            ->searchable()
                            ->preload()
                            ->visible(fn($get) => $get('faculty_id')),
                        Forms\Components\Select::make('unit_id')
                            ->label('Unit Terkait')
                            ->relationship('unit', 'name')
                            ->searchable()
                            ->preload(),
                    ])->columns(2),
            ])
            ->beforeSave(function ($data, $record) {
                if (!$record) {
                    $data['uploaded_by'] = Auth::id();

                    if (isset($data['file_path'])) {
                        $fileName = pathinfo($data['file_path'], PATHINFO_BASENAME);
                        $fileType = Storage::mimeType($data['file_path']);
                        $fileSize = Storage::size($data['file_path']);

                        $data['file_name'] = $fileName;
                        $data['file_type'] = $fileType;
                        $data['file_size'] = self::formatBytes($fileSize);
                    }
                }

                $data['updated_by'] = Auth::id();

                return $data;
            });
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('visibility')
                    ->label('Visibilitas')
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('file_name')
                    ->label('Nama File')
                    ->searchable(),
                Tables\Columns\TextColumn::make('file_size')
                    ->label('Ukuran')
                    ->searchable(),
                Tables\Columns\TextColumn::make('standard.name')
                    ->label('Standar Terkait')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('faculty.name')
                    ->label('Fakultas')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('department.name')
                    ->label('Program Studi')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('uploader.name')
                    ->label('Diupload Oleh')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Upload')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Kategori')
                    ->options([
                        'SOP' => 'Standar Operasional Prosedur',
                        'Pedoman' => 'Pedoman',
                        'Kebijakan' => 'Kebijakan',
                        'Laporan' => 'Laporan',
                        'Instruksi Kerja' => 'Instruksi Kerja',
                        'Formulir' => 'Formulir',
                        'Lainnya' => 'Lainnya',
                    ]),
                Tables\Filters\SelectFilter::make('visibility')
                    ->label('Visibilitas')
                    ->options([
                        'public' => 'Publik',
                        'private' => 'Privat',
                        'restricted' => 'Terbatas',
                    ]),
                Tables\Filters\SelectFilter::make('standard_id')
                    ->label('Standar')
                    ->relationship('standard', 'name'),
                Tables\Filters\SelectFilter::make('faculty_id')
                    ->label('Fakultas')
                    ->relationship('faculty', 'name'),
                Tables\Filters\SelectFilter::make('department_id')
                    ->label('Program Studi')
                    ->relationship('department', 'name'),
                Tables\Filters\SelectFilter::make('unit_id')
                    ->label('Unit')
                    ->relationship('unit', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('download')
                    ->label('Download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn(Document $record) => Storage::url($record->file_path))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDocuments::route('/'),
            'create' => Pages\CreateDocument::route('/create'),
            'edit' => Pages\EditDocument::route('/{record}/edit'),
        ];
    }

    private static function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
