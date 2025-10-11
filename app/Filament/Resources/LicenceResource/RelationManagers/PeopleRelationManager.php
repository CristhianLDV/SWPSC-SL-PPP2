<?php

namespace App\Filament\Resources\LicenceResource\RelationManagers;

use App\Models\LicencePerson;
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
    public static function getTitle(Model $ownerRecord, string $pageClass): string
            {
                return 'Responsables'; // Traducción del título de la pestaña
            }
    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        return $ownerRecord->people()->count();
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('Nombre')
                ->required()
                ->maxLength(255),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->allowDuplicates()
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
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
                    ->label('Fecha de asignación')
                    ->alignRight(),

                Tables\Columns\TextColumn::make('checked_in_at')
                    ->label('Fecha de devolución')
                    ->alignRight(),
            ])
            ->filters([])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    // ✅ Previene duplicados activos (personas con licencia aún sin devolver)
                    ->disabled(function (RelationManager $livewire) {
                        return LicencePerson::whereLicenceId($livewire->ownerRecord->id)
                            ->whereNull('checked_in_at')
                            ->exists();
                    })
                    ->label('Vincular responsable'),
            ])
            ->actions([
                Tables\Actions\Action::make('check_in')
                    ->label('Desvincular')
                    ->requiresConfirmation()
                    // ✅ Acceso seguro al pivot, evita errores si no existe
                    ->action(function (Person $record) {
                        $record->pivot?->touch('checked_in_at');
                    })
                    ->visible(fn (Person $record) => empty($record->checked_in_at)),
            ])
            ->bulkActions([]);
    }
}
