<?php

namespace App\Filament\Resources\SurveyResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuestionsRelationManager extends RelationManager
{
    protected static string $relationship = 'questions';

    protected static ?string $recordTitleAttribute = 'question';

    protected static ?string $title = 'Pertanyaan';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('question')
                    ->label('Pertanyaan')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\Select::make('type')
                    ->label('Tipe Pertanyaan')
                    ->options([
                        'text' => 'Teks',
                        'number' => 'Angka',
                        'multiple_choice' => 'Pilihan Ganda',
                        'checkbox' => 'Kotak Centang',
                        'scale' => 'Skala',
                        'dropdown' => 'Dropdown',
                    ])
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Forms\Set $set) {
                        $set('options', null);
                        $set('min_value', null);
                        $set('max_value', null);
                        $set('min_label', null);
                        $set('max_label', null);
                    }),
                Forms\Components\Grid::make()
                    ->schema([
                        Forms\Components\Repeater::make('options')
                            ->label('Opsi Jawaban')
                            ->schema([
                                Forms\Components\TextInput::make('option')
                                    ->label('Opsi')
                                    ->required(),
                            ])
                            ->columns(1)
                            ->addActionLabel('Tambah Opsi')
                            ->required()
                            ->minItems(2)
                            ->visible(fn(Forms\Get $get) => in_array($get('type'), ['multiple_choice', 'checkbox', 'dropdown']))
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Grid::make()
                    ->schema([
                        Forms\Components\TextInput::make('min_value')
                            ->label('Nilai Minimum')
                            ->numeric()
                            ->required()
                            ->visible(fn(Forms\Get $get) => $get('type') === 'scale'),
                        Forms\Components\TextInput::make('max_value')
                            ->label('Nilai Maksimum')
                            ->numeric()
                            ->required()
                            ->visible(fn(Forms\Get $get) => $get('type') === 'scale')
                            ->rules([
                                fn(Forms\Get $get): \Closure => function (string $attribute, $value, \Closure $fail) use ($get) {
                                    if ($value <= $get('min_value')) {
                                        $fail('Nilai maksimum harus lebih besar dari nilai minimum.');
                                    }
                                },
                            ]),
                        Forms\Components\TextInput::make('min_label')
                            ->label('Label Minimum')
                            ->visible(fn(Forms\Get $get) => $get('type') === 'scale'),
                        Forms\Components\TextInput::make('max_label')
                            ->label('Label Maksimum')
                            ->visible(fn(Forms\Get $get) => $get('type') === 'scale'),
                    ])
                    ->columns(2),
                Forms\Components\TextInput::make('order')
                    ->label('Urutan')
                    ->numeric()
                    ->default(0),
                Forms\Components\Toggle::make('is_required')
                    ->label('Wajib Diisi')
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('question')
            ->columns([
                Tables\Columns\TextColumn::make('order')
                    ->label('Urutan')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('question')
                    ->label('Pertanyaan')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'text' => 'Teks',
                        'number' => 'Angka',
                        'multiple_choice' => 'Pilihan Ganda',
                        'checkbox' => 'Kotak Centang',
                        'scale' => 'Skala',
                        'dropdown' => 'Dropdown',
                        default => $state,
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'text' => 'blue',
                        'number' => 'sky',
                        'multiple_choice' => 'green',
                        'checkbox' => 'indigo',
                        'scale' => 'purple',
                        'dropdown' => 'orange',
                        default => 'gray',
                    }),
                Tables\Columns\IconColumn::make('is_required')
                    ->label('Wajib')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tambah Pertanyaan')
                    ->mutateFormDataUsing(function (array $data) {
                        // Konversi options menjadi array jika perlu
                        if (isset($data['options']) && is_array($data['options'])) {
                            $options = [];
                            foreach ($data['options'] as $option) {
                                $options[] = $option['option'];
                            }
                            $data['options'] = $options;
                        }
                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->mutateRecordDataUsing(function (array $data) {
                        // Mengkonversi array options menjadi format yang sesuai dengan form
                        if (isset($data['options']) && is_array($data['options'])) {
                            $formattedOptions = [];
                            foreach ($data['options'] as $option) {
                                $formattedOptions[] = ['option' => $option];
                            }
                            $data['options'] = $formattedOptions;
                        }
                        return $data;
                    })
                    ->mutateFormDataUsing(function (array $data) {
                        // Konversi options menjadi array flat kembali
                        if (isset($data['options']) && is_array($data['options'])) {
                            $options = [];
                            foreach ($data['options'] as $option) {
                                $options[] = $option['option'];
                            }
                            $data['options'] = $options;
                        }
                        return $data;
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('order', 'asc')
            ->reorderable('order');
    }
}
