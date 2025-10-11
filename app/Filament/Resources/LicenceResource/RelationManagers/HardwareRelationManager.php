<?php

namespace App\Filament\Resources\LicenceResource\RelationManagers;

use App\Models\Hardware;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class HardwareRelationManager extends RelationManager
{
    protected static string $relationship = 'Hardware';
    protected bool $allowsDuplicates = true;
    protected static ?string $recordTitleAttribute = 'name';
    public static function getTitle(Model $ownerRecord, string $pageClass): string
            {
                return 'Equipos'; // Traducción del título de la pestaña
            }
    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        return $ownerRecord->hardware()->count();
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
                TextColumn::make('hardware_model.name')
                    ->label('Modelo')
                    ->badge()
                    ->url(fn (Hardware $record) => "/admin/hardware/{$record->hardware_id}/edit")
                    ->iconPosition('after')
                    ->icon('heroicon-o-arrow-right')
                    ->searchable(),

                TextColumn::make('hardware_status.name')
                    ->label('Estado')
                    ->sortable()
                    ->badge()
                    ->color('success')
                    ->searchable()
                    ->iconPosition('after'),

                TextColumn::make('serial_number')
                    ->label('Número de serie')
                    ->sortable()
                    ->alignRight()
                    ->searchable()
                    ->badge()
                    ->color('info'),

                TextColumn::make('checked_out_at')
                    ->label('Fecha de asignación')
                    ->alignRight(),

                TextColumn::make('checked_in_at')
                    ->label('Fecha de devolución')
                    ->alignRight(),
            ])
            ->filters([])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->disabled(false)
                    ->label('Vincular Equipo'),
            ])
            ->actions([
                Tables\Actions\Action::make('check_in')
                    ->label('Desvincular')
                    ->requiresConfirmation()
                    ->action(function (Hardware $record) {
                        // Evita error si pivot_id no existe
                        $record->pivot?->touch('checked_in_at');
                    })
                    ->visible(fn (Hardware $record) => empty($record->checked_in_at)),
            ])
            ->bulkActions([]);
    }
}
