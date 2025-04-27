<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuditResource\Pages;
use App\Filament\Resources\AuditResource\RelationManagers;
use App\Models\Audit;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class AuditResource extends Resource
{
    protected static ?string $model = Audit::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Manajemen SPMI';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Audit')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Judul Audit')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'planned' => 'Direncanakan',
                                'ongoing' => 'Sedang Berlangsung',
                                'completed' => 'Selesai',
                                'cancelled' => 'Dibatalkan',
                            ])
                            ->required()
                            ->default('planned'),
                        Forms\Components\DatePicker::make('audit_date_start')
                            ->label('Tanggal Mulai')
                            ->required(),
                        Forms\Components\DatePicker::make('audit_date_end')
                            ->label('Tanggal Selesai')
                            ->required()
                            ->after('audit_date_start'),
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Auditi')
                    ->schema([
                        Forms\Components\Select::make('faculty_id')
                            ->label('Fakultas')
                            ->relationship('faculty', 'name')
                            ->searchable()
                            ->preload()
                            ->live(),
                        Forms\Components\Select::make('department_id')
                            ->label('Program Studi')
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
                            ->label('Unit')
                            ->relationship('unit', 'name')
                            ->searchable()
                            ->preload(),
                    ])->columns(2),

                Forms\Components\Section::make('Tim Auditor')
                    ->schema([
                        Forms\Components\Select::make('lead_auditor_id')
                            ->label('Lead Auditor')
                            ->options(function () {
                                return User::role('auditor')->pluck('name', 'id')->toArray();
                            })
                            ->searchable()
                            ->required(),
                        Forms\Components\Select::make('auditors')
                            ->label('Auditor')
                            ->relationship('auditors', 'name')
                            ->options(function () {
                                return User::role(['auditor', 'kepala-lpm'])->pluck('name', 'id')->toArray();
                            })
                            ->multiple()
                            ->searchable(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    // ->wrap()
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'planned',
                        'primary' => 'ongoing',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                    ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'planned' => 'Direncanakan',
                        'ongoing' => 'Sedang Berlangsung',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                        default => $state,
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('audit_date_start')
                    ->label('Mulai')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('audit_date_end')
                    ->label('Selesai')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('faculty.name')
                    ->label('Audite')
                    ->searchable()
                    ->toggleable()
                    ->description(fn(Audit $record): string => $record->department->name ?? ''),
                Tables\Columns\TextColumn::make('unit.name')
                    ->label('Unit')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('leadAuditor.name')
                    ->label('Lead Auditor')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'planned' => 'Direncanakan',
                        'ongoing' => 'Sedang Berlangsung',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ]),
                Tables\Filters\SelectFilter::make('faculty_id')
                    ->label('Fakultas')
                    ->relationship('faculty', 'name'),
                Tables\Filters\SelectFilter::make('department_id')
                    ->label('Program Studi')
                    ->relationship('department', 'name'),
                Tables\Filters\SelectFilter::make('unit_id')
                    ->label('Unit')
                    ->relationship('unit', 'name'),
                Tables\Filters\SelectFilter::make('lead_auditor_id')
                    ->label('Lead Auditor')
                    ->relationship('leadAuditor', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('start_audit')
                    ->label('Mulai Audit')
                    ->icon('heroicon-o-play')
                    ->color('primary')
                    ->visible(fn(Audit $record) => $record->status === 'planned')
                    ->action(function (Audit $record) {
                        $record->status = 'ongoing';
                        $record->save();
                    }),
                Tables\Actions\Action::make('complete_audit')
                    ->label('Selesaikan Audit')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn(Audit $record) => $record->status === 'ongoing')
                    ->action(function (Audit $record) {
                        $record->status = 'completed';
                        $record->save();
                    }),
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
            RelationManagers\FindingsRelationManager::class,
            RelationManagers\AuditorsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAudits::route('/'),
            'create' => Pages\CreateAudit::route('/create'),
            'view' => Pages\ViewAudit::route('/{record}'),
            'edit' => Pages\EditAudit::route('/{record}/edit'),
        ];
    }
}
