<?php

namespace App\Filament\Resources\ConsumableResource\RelationManagers;

use App\Models\Person;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class PeopleRelationManager extends RelationManager
{
    protected static string $relationship = 'people';
    protected static ?string $recordTitleAttribute = 'name';

    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        return $ownerRecord->people()->count();
    }

    public function table(Table $table): Table
    {
        return $table
            ->allowDuplicates()
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->badge()
                    ->searchable()
                    ->url(fn (Person $record) => "/admin/people/{$record->person_id}/edit")
                    ->getStateUsing(fn (Person $record): string => $record->name)
                    ->iconPosition('after')
                    ->icon('heroicon-o-arrow-right'),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Teléfono')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Correo electrónico')
                    ->searchable(),

                Tables\Columns\TextColumn::make('checked_out_at')
                    ->label('Fecha de retiro')
                    ->alignRight(),
            ])
            ->filters([])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->label('Adjuntar persona'),
            ]);
    }
}
