<?php

namespace App\Filament\Resources\AuditResource\RelationManagers;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AuditorsRelationManager extends RelationManager
{
    protected static string $relationship = 'auditors';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Auditor')
                    ->options(function () {
                        return User::role(['auditor', 'kepala-lpm'])
                            ->pluck('name', 'id')
                            ->toArray();
                    })
                    ->required()
                    ->searchable(),
                Forms\Components\Select::make('role')
                    ->label('Peran')
                    ->options([
                        'lead_auditor' => 'Lead Auditor',
                        'auditor' => 'Auditor',
                        'observer' => 'Observer',
                    ])
                    ->default('auditor')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('pivot.role')
                    ->label('Peran')
                    ->colors([
                        'primary' => 'lead_auditor',
                        'success' => 'auditor',
                        'gray' => 'observer',
                    ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'lead_auditor' => 'Lead Auditor',
                        'auditor' => 'Auditor',
                        'observer' => 'Observer',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('profile.position')
                    ->label('Jabatan')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('profile.faculty.name')
                    ->label('Fakultas')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('profile.department.name')
                    ->label('Program Studi')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->form(fn(Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect()
                            ->label('Auditor')
                            ->required(),
                        Forms\Components\Select::make('role')
                            ->label('Peran')
                            ->options([
                                'lead_auditor' => 'Lead Auditor',
                                'auditor' => 'Auditor',
                                'observer' => 'Observer',
                            ])
                            ->default('auditor')
                            ->required(),
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->form(fn(Tables\Actions\EditAction $action): array => [
                        Forms\Components\Select::make('role')
                            ->label('Peran')
                            ->options([
                                'lead_auditor' => 'Lead Auditor',
                                'auditor' => 'Auditor',
                                'observer' => 'Observer',
                            ])
                            ->default('auditor')
                            ->required(),
                    ]),
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}
