<?php

namespace App\Filament\Resources\FacultyResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DepartmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'departments';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Program Studi')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('code')
                    ->label('Kode Program Studi')
                    ->required()
                    ->maxLength(20),
                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')
                    ->rows(3),
                Forms\Components\Select::make('head_id')
                    ->label('Ketua Program Studi')
                    ->relationship(
                        'head',
                        'name',
                        fn(Builder $query) =>
                        $query->whereHas(
                            'roles',
                            fn($q) =>
                            $q->where('name', 'kaprodi')
                        )
                    )
                    ->searchable()
                    ->preload(),
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('accreditation')
                            ->label('Akreditasi')
                            ->maxLength(50),
                        Forms\Components\DatePicker::make('accreditation_date')
                            ->label('Tanggal Akreditasi'),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Program Studi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('head.name')
                    ->label('Kaprodi')
                    ->sortable(),
                Tables\Columns\TextColumn::make('accreditation')
                    ->label('Akreditasi')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'A' => 'success',
                        'B' => 'warning',
                        'C' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('accreditation_date')
                    ->label('Tanggal Akreditasi')
                    ->date(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
