<?php

namespace App\Filament\Resources\AuditResource\RelationManagers;

use App\Models\AuditFinding;
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
use Illuminate\Support\Facades\Notification;

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
                Tables\Actions\Action::make('followup')
                    ->label('Tindak Lanjut')
                    ->icon('heroicon-o-clipboard-check')
                    ->color('success')
                    ->visible(fn(AuditFinding $record) => in_array($record->status, ['open', 'responded']) && is_null($record->followup_action))
                    ->form([
                        Forms\Components\Textarea::make('followup_action')
                            ->label('Tindakan Perbaikan')
                            ->required(),
                        Forms\Components\DatePicker::make('followup_date')
                            ->label('Tanggal Tindak Lanjut')
                            ->default(now())
                            ->required(),
                    ])
                    ->action(function (AuditFinding $record, array $data): void {
                        $record->update([
                            'followup_action' => $data['followup_action'],
                            'followup_date' => $data['followup_date'],
                            'followup_by' => auth()->id(),
                            'status' => 'in_progress',
                        ]);
                        Notification::make()
                            ->title('Tindak lanjut berhasil dicatat')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('verify')
                    ->label('Verifikasi')
                    ->icon('heroicon-o-check-badge')
                    ->color('primary')
                    ->visible(fn(AuditFinding $record) => $record->status === 'in_progress' && !is_null($record->followup_action) && is_null($record->verification_notes))
                    ->form([
                        Forms\Components\Textarea::make('verification_notes')
                            ->label('Catatan Verifikasi')
                            ->required(),
                        Forms\Components\DatePicker::make('verification_date')
                            ->label('Tanggal Verifikasi')
                            ->default(now())
                            ->required(),
                        Forms\Components\Select::make('new_status')
                            ->label('Status Baru')
                            ->options([
                                'verified' => 'Terverifikasi',
                                'closed' => 'Ditutup',
                            ])
                            ->required(),
                    ])
                    ->action(function (AuditFinding $record, array $data): void {
                        $record->update([
                            'verification_notes' => $data['verification_notes'],
                            'verification_date' => $data['verification_date'],
                            'verified_by' => auth()->id(),
                            'status' => $data['new_status'],
                        ]);
                        Notification::make()
                            ->title('Verifikasi berhasil dicatat')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
