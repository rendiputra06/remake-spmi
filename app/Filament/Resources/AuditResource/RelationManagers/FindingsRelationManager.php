<?php

namespace App\Filament\Resources\AuditResource\RelationManagers;

use App\Models\Standard;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class FindingsRelationManager extends RelationManager
{
    protected static string $relationship = 'findings';

    protected static ?string $recordTitleAttribute = 'finding';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('type')
                    ->label('Tipe Temuan')
                    ->options([
                        'observation' => 'Observasi',
                        'minor' => 'Minor',
                        'major' => 'Major',
                        'opportunity' => 'Peluang Perbaikan',
                    ])
                    ->required(),
                Forms\Components\Select::make('standard_id')
                    ->label('Standar Terkait')
                    ->relationship('standard', 'name')
                    ->searchable()
                    ->preload(),
                Forms\Components\Textarea::make('finding')
                    ->label('Temuan')
                    ->required()
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('evidence')
                    ->label('Bukti')
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('recommendation')
                    ->label('Rekomendasi')
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'open' => 'Terbuka',
                        'responded' => 'Direspon',
                        'in_progress' => 'Dalam Proses',
                        'verified' => 'Diverifikasi',
                        'closed' => 'Ditutup',
                    ])
                    ->required()
                    ->default('open'),
                Forms\Components\DatePicker::make('target_completion_date')
                    ->label('Target Penyelesaian')
                    ->after('now'),
                Forms\Components\Textarea::make('response')
                    ->label('Respon')
                    ->rows(3)
                    ->columnSpanFull()
                    ->visible(fn(string $operation): bool => $operation === 'edit'),
                Forms\Components\Textarea::make('action_plan')
                    ->label('Rencana Tindakan')
                    ->rows(3)
                    ->columnSpanFull()
                    ->visible(fn(string $operation): bool => $operation === 'edit'),
                Forms\Components\DatePicker::make('response_date')
                    ->label('Tanggal Respon')
                    ->visible(fn(string $operation): bool => $operation === 'edit'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\BadgeColumn::make('type')
                    ->label('Tipe')
                    ->colors([
                        'primary' => 'observation',
                        'warning' => 'minor',
                        'danger' => 'major',
                        'success' => 'opportunity',
                    ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'observation' => 'Observasi',
                        'minor' => 'Minor',
                        'major' => 'Major',
                        'opportunity' => 'Peluang Perbaikan',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('finding')
                    ->label('Temuan')
                    ->wrap()
                    ->searchable()
                    ->limit(50),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'primary' => 'open',
                        'info' => 'responded',
                        'warning' => 'in_progress',
                        'success' => 'verified',
                        'gray' => 'closed',
                    ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'open' => 'Terbuka',
                        'responded' => 'Direspon',
                        'in_progress' => 'Dalam Proses',
                        'verified' => 'Diverifikasi',
                        'closed' => 'Ditutup',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('standard.name')
                    ->label('Standar')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Dibuat Oleh')
                    ->searchable(),
                Tables\Columns\TextColumn::make('target_completion_date')
                    ->label('Target Selesai')
                    ->date(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Tipe')
                    ->options([
                        'observation' => 'Observasi',
                        'minor' => 'Minor',
                        'major' => 'Major',
                        'opportunity' => 'Peluang Perbaikan',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'open' => 'Terbuka',
                        'responded' => 'Direspon',
                        'in_progress' => 'Dalam Proses',
                        'verified' => 'Diverifikasi',
                        'closed' => 'Ditutup',
                    ]),
                Tables\Filters\SelectFilter::make('standard_id')
                    ->label('Standar')
                    ->relationship('standard', 'name'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataBeforeCreate(function (array $data): array {
                        $data['created_by'] = auth()->id();
                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('respond')
                    ->label('Respon')
                    ->icon('heroicon-o-chat-bubble-left')
                    ->color('info')
                    ->form([
                        Forms\Components\Textarea::make('response')
                            ->label('Respon')
                            ->required()
                            ->rows(3),
                        Forms\Components\Textarea::make('action_plan')
                            ->label('Rencana Tindakan')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function (array $data, $record) {
                        $record->response = $data['response'];
                        $record->action_plan = $data['action_plan'];
                        $record->response_date = now();
                        $record->responded_by = Auth::id();
                        $record->status = 'responded';
                        $record->save();
                    })
                    ->visible(fn($record) => $record->status === 'open'),
                Tables\Actions\Action::make('verify')
                    ->label('Verifikasi')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->action(function ($record) {
                        $record->verified_by = Auth::id();
                        $record->status = 'verified';
                        $record->save();
                    })
                    ->requiresConfirmation()
                    ->visible(fn($record) => in_array($record->status, ['responded', 'in_progress'])),
                Tables\Actions\Action::make('close')
                    ->label('Tutup')
                    ->icon('heroicon-o-archive-box')
                    ->color('gray')
                    ->action(function ($record) {
                        $record->status = 'closed';
                        $record->save();
                    })
                    ->requiresConfirmation()
                    ->visible(fn($record) => $record->status === 'verified'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
