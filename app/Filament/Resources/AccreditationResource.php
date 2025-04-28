<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccreditationResource\Pages;
use App\Filament\Resources\AccreditationResource\RelationManagers;
use App\Models\Accreditation;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class AccreditationResource extends Resource
{
    protected static ?string $model = Accreditation::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'Manajemen Akreditasi';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Akreditasi')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Judul')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(3),
                        Forms\Components\Select::make('type')
                            ->label('Tipe')
                            ->options([
                                'institution' => 'Institusi',
                                'faculty' => 'Fakultas',
                                'department' => 'Program Studi',
                                'program' => 'Program Lainnya',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('institution_name')
                            ->label('Nama Lembaga Akreditasi')
                            ->placeholder('Contoh: BAN-PT, LAMDIK, dsb')
                            ->maxLength(255),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'draft' => 'Draft',
                                'in_progress' => 'Sedang Berjalan',
                                'submitted' => 'Diajukan',
                                'completed' => 'Selesai',
                            ])
                            ->default('draft')
                            ->required(),
                        Forms\Components\TextInput::make('grade')
                            ->label('Nilai Akreditasi')
                            ->maxLength(10)
                            ->placeholder('Contoh: A, Unggul, dsb'),
                    ])->columns(2),

                Forms\Components\Section::make('Entitas Terkait')
                    ->schema([
                        Forms\Components\Select::make('faculty_id')
                            ->label('Fakultas')
                            ->relationship('faculty', 'name')
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('department_id')
                            ->label('Program Studi')
                            ->relationship('department', 'name', function (Builder $query, callable $get) {
                                $facultyId = $get('faculty_id');
                                if ($facultyId) {
                                    $query->where('faculty_id', $facultyId);
                                }
                            })
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('coordinator_id')
                            ->label('Koordinator')
                            ->relationship('coordinator', 'name', function (Builder $query) {
                                // Filter user-user yang memiliki peran terkait akreditasi
                                $query->whereHas('roles', function (Builder $subQuery) {
                                    $subQuery->whereIn('name', ['super-admin', 'admin', 'kepala-lpm']);
                                });
                            })
                            ->searchable()
                            ->preload(),
                    ])->columns(3),

                Forms\Components\Section::make('Tanggal Penting')
                    ->schema([
                        Forms\Components\DatePicker::make('submission_date')
                            ->label('Tanggal Pengajuan')
                            ->placeholder('Pilih tanggal pengajuan'),
                        Forms\Components\DatePicker::make('visit_date')
                            ->label('Tanggal Visitasi')
                            ->placeholder('Pilih tanggal visitasi'),
                        Forms\Components\DatePicker::make('result_date')
                            ->label('Tanggal Hasil')
                            ->placeholder('Pilih tanggal hasil'),
                        Forms\Components\DatePicker::make('expiry_date')
                            ->label('Tanggal Kedaluwarsa')
                            ->placeholder('Pilih tanggal kedaluwarsa'),
                    ])->columns(2),
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
                Tables\Columns\BadgeColumn::make('type')
                    ->label('Tipe')
                    ->sortable()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'institution' => 'Institusi',
                        'faculty' => 'Fakultas',
                        'department' => 'Program Studi',
                        'program' => 'Program Lainnya',
                        default => $state,
                    })
                    ->colors([
                        'primary' => 'institution',
                        'success' => 'faculty',
                        'warning' => 'department',
                        'danger' => 'program',
                    ]),
                Tables\Columns\TextColumn::make('institution_name')
                    ->label('Lembaga Akreditasi')
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->sortable()
                    ->colors([
                        'gray' => 'draft',
                        'info' => 'in_progress',
                        'warning' => 'submitted',
                        'success' => 'completed',
                    ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'draft' => 'Draft',
                        'in_progress' => 'Sedang Berjalan',
                        'submitted' => 'Diajukan',
                        'completed' => 'Selesai',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('grade')
                    ->label('Nilai')
                    ->sortable(),
                Tables\Columns\TextColumn::make('faculty.name')
                    ->label('Fakultas')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('department.name')
                    ->label('Program Studi')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('submission_date')
                    ->label('Tanggal Pengajuan')
                    ->date()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('expiry_date')
                    ->label('Kedaluwarsa')
                    ->date()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('coordinator.name')
                    ->label('Koordinator')
                    ->searchable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'in_progress' => 'Sedang Berjalan',
                        'submitted' => 'Diajukan',
                        'completed' => 'Selesai',
                    ]),
                Tables\Filters\SelectFilter::make('type')
                    ->label('Tipe')
                    ->options([
                        'institution' => 'Institusi',
                        'faculty' => 'Fakultas',
                        'department' => 'Program Studi',
                        'program' => 'Program Lainnya',
                    ]),
                Tables\Filters\SelectFilter::make('faculty_id')
                    ->label('Fakultas')
                    ->relationship('faculty', 'name')
                    ->searchable(),
                Tables\Filters\SelectFilter::make('department_id')
                    ->label('Program Studi')
                    ->relationship('department', 'name')
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            RelationManagers\StandardsRelationManager::class,
            RelationManagers\DocumentsRelationManager::class,
            RelationManagers\EvaluationsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAccreditations::route('/'),
            'create' => Pages\CreateAccreditation::route('/create'),
            'edit' => Pages\EditAccreditation::route('/{record}/edit'),
        ];
    }
}
