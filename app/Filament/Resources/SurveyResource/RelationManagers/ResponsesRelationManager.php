<?php

namespace App\Filament\Resources\SurveyResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\SurveyAnswer;
use Filament\Infolists\Infolist;
use Filament\Infolists;

class ResponsesRelationManager extends RelationManager
{
    protected static string $relationship = 'responses';

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $title = 'Respons';

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Informasi Responden')
                    ->schema([
                        Infolists\Components\TextEntry::make('user.name')
                            ->label('Nama Responden')
                            ->visible(fn($record) => $record->user_id !== null),
                        Infolists\Components\TextEntry::make('respondent_type')
                            ->label('Tipe Responden'),
                        Infolists\Components\TextEntry::make('respondent_id')
                            ->label('ID Responden'),
                        Infolists\Components\TextEntry::make('submitted_at')
                            ->label('Tanggal Pengisian')
                            ->dateTime('d M Y H:i'),
                        Infolists\Components\TextEntry::make('ip_address')
                            ->label('Alamat IP')
                            ->visible(fn($record) => $record->ip_address !== null),
                    ])
                    ->columns(2),
                Infolists\Components\Section::make('Jawaban')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('answers')
                            ->label('')
                            ->schema([
                                Infolists\Components\TextEntry::make('question.question')
                                    ->label('Pertanyaan')
                                    ->weight('bold')
                                    ->columnSpanFull(),
                                Infolists\Components\TextEntry::make('answer_text')
                                    ->label('Jawaban Teks')
                                    ->visible(fn($record) => $record->question->type === 'text' && $record->answer_text !== null),
                                Infolists\Components\TextEntry::make('answer_number')
                                    ->label('Jawaban Angka')
                                    ->visible(fn($record) => $record->question->type === 'number' && $record->answer_number !== null),
                                Infolists\Components\TextEntry::make('formatted_answer')
                                    ->label('Jawaban')
                                    ->state(function ($record) {
                                        // Jika tipe pertanyaan adalah scale
                                        if ($record->question->type === 'scale' && $record->answer_number !== null) {
                                            return "Nilai: {$record->answer_number} (dari {$record->question->min_value} sampai {$record->question->max_value})";
                                        }

                                        // Jika tipe pertanyaan adalah multiple_choice atau dropdown
                                        if (in_array($record->question->type, ['multiple_choice', 'dropdown']) && $record->answer_options !== null) {
                                            $options = json_decode($record->answer_options, true);
                                            if (is_array($options) && count($options) === 1) {
                                                return $options[0];
                                            }
                                        }

                                        // Jika tipe pertanyaan adalah checkbox
                                        if ($record->question->type === 'checkbox' && $record->answer_options !== null) {
                                            $options = json_decode($record->answer_options, true);
                                            if (is_array($options) && count($options) > 0) {
                                                return implode(', ', $options);
                                            }
                                        }

                                        return 'Tidak ada jawaban';
                                    })
                                    ->visible(fn($record) => in_array($record->question->type, ['scale', 'multiple_choice', 'checkbox', 'dropdown'])),
                            ])
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Responden')
                    ->searchable()
                    ->placeholder('Anonim'),
                Tables\Columns\TextColumn::make('respondent_type')
                    ->label('Tipe')
                    ->searchable(),
                Tables\Columns\TextColumn::make('submitted_at')
                    ->label('Tanggal Pengisian')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_completed')
                    ->label('Selesai')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle'),
                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP Address')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('is_completed')
                    ->label('Status')
                    ->options([
                        '1' => 'Selesai',
                        '0' => 'Belum Selesai',
                    ]),
            ])
            ->headerActions([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
