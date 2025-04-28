<?php

namespace App\Filament\Resources\AccreditationResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class StandardsRelationManager extends RelationManager
{
    protected static string $relationship = 'standards';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Standar')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('code')
                    ->label('Kode Standar')
                    ->required()
                    ->maxLength(50),
                Forms\Components\Select::make('category')
                    ->label('Kategori')
                    ->options([
                        'academic' => 'Akademik',
                        'facilities' => 'Fasilitas',
                        'organization' => 'Organisasi',
                        'management' => 'Manajemen',
                        'research' => 'Penelitian',
                        'community_service' => 'Pengabdian Masyarakat',
                        'other' => 'Lainnya',
                    ])
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')
                    ->rows(3),
                Forms\Components\TextInput::make('weight')
                    ->label('Bobot')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->default(0),
                Forms\Components\TextInput::make('max_score')
                    ->label('Skor Maksimum')
                    ->numeric()
                    ->minValue(0)
                    ->default(100),
                Forms\Components\Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Standar')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->searchable()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'academic' => 'Akademik',
                        'facilities' => 'Fasilitas',
                        'organization' => 'Organisasi',
                        'management' => 'Manajemen',
                        'research' => 'Penelitian',
                        'community_service' => 'Pengabdian Masyarakat',
                        'other' => 'Lainnya',
                        default => $state,
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'academic' => 'info',
                        'facilities' => 'success',
                        'organization' => 'warning',
                        'management' => 'primary',
                        'research' => 'danger',
                        'community_service' => 'gray',
                        default => 'secondary',
                    }),
                Tables\Columns\TextColumn::make('weight')
                    ->label('Bobot')
                    ->sortable(),
                Tables\Columns\TextColumn::make('max_score')
                    ->label('Skor Maksimum')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Kategori')
                    ->options([
                        'academic' => 'Akademik',
                        'facilities' => 'Fasilitas',
                        'organization' => 'Organisasi',
                        'management' => 'Manajemen',
                        'research' => 'Penelitian',
                        'community_service' => 'Pengabdian Masyarakat',
                        'other' => 'Lainnya',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tambah Standar')
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
            ])
            ->defaultSort('code');
    }
}
