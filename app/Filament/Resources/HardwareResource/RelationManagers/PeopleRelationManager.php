<?php

namespace App\Filament\Resources\HardwareResource\RelationManagers;

use App\Models\HardwarePerson;
use App\Models\Person;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class PeopleRelationManager extends RelationManager
{
    protected static string $relationship = 'people';
    protected bool $allowsDuplicates = true;
    protected static ?string $recordTitleAttribute = 'name';

    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        return $ownerRecord->people()->count();
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->required()
                ->label('Nombre')
                ->maxLength(255),
        ]);
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
                    ->label('Fecha de entrega')
                    ->alignRight(),

                Tables\Columns\TextColumn::make('checked_in_at')
                    ->label('Fecha de devolución')
                    ->alignRight(),
            ])
            ->filters([])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->disabled(function (RelationManager $livewire) {
                        // Evita asignar si ya hay un préstamo activo
                        return HardwarePerson::whereHardwareId($livewire->ownerRecord->id)
                            ->whereNull('checked_in_at')
                            ->exists();
                    })
                    ->label('Asignar persona'),
            ])
            ->actions([
                Tables\Actions\Action::make('check_in')
                    ->label('Desvincular')
                    ->action(function (Person $record) {
                        $record->pivot->find($record->pivot_id)?->touch('checked_in_at');
                    })
                    ->requiresConfirmation()
                    ->visible(fn (Person $record) => empty($record->checked_in_at)),
            ])
            ->bulkActions([]);
    }
}
