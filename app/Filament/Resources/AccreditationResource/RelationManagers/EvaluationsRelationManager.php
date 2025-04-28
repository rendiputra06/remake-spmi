<?php

namespace App\Filament\Resources\AccreditationResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class EvaluationsRelationManager extends RelationManager
{
    protected static string $relationship = 'evaluations';

    protected static ?string $recordTitleAttribute = 'title';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Judul Evaluasi')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')
                    ->rows(3),
                Forms\Components\DatePicker::make('evaluation_date')
                    ->label('Tanggal Evaluasi')
                    ->required(),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'in_progress' => 'Dalam Proses',
                        'completed' => 'Selesai',
                        'reviewed' => 'Sudah Ditinjau',
                    ])
                    ->default('draft')
                    ->required(),
                Forms\Components\TextInput::make('score')
                    ->label('Skor')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100),
                Forms\Components\Textarea::make('findings')
                    ->label('Temuan')
                    ->rows(4),
                Forms\Components\Textarea::make('recommendations')
                    ->label('Rekomendasi')
                    ->rows(4),
                Forms\Components\Select::make('evaluator_id')
                    ->label('Evaluator')
                    ->relationship('evaluator', 'name')
                    ->searchable()
                    ->preload(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Evaluasi')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('evaluation_date')
                    ->label('Tanggal Evaluasi')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'draft' => 'gray',
                        'in_progress' => 'warning',
                        'completed' => 'success',
                        'reviewed' => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('score')
                    ->label('Skor')
                    ->numeric(2),
                Tables\Columns\TextColumn::make('evaluator.name')
                    ->label('Evaluator')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'in_progress' => 'Dalam Proses',
                        'completed' => 'Selesai',
                        'reviewed' => 'Sudah Ditinjau',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tambah Evaluasi')
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
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
