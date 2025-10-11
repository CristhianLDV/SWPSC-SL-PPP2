<?php

namespace App\Filament\Resources\ComponentResource\RelationManagers;

use App\Models\Hardware;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class HardwareRelationManager extends RelationManager
{
    protected static string $relationship = 'hardware';
    protected static ?string $recordTitleAttribute = 'name';
     public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return 'Equipos'; // Traducción del título de la pestaña
    }
    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        return $ownerRecord->hardware()->count();
    }

    public function table(Table $table): Table
    {
        return $table
            ->allowDuplicates()
            ->columns([
                TextColumn::make('hardware_model.name')
                    ->badge()
                    ->url(fn (Hardware $record) => "/admin/hardware/{$record->hardware_id}/edit")
                    ->label('Modelo')
                    ->iconPosition('after')
                    ->searchable()
                    ->icon('heroicon-o-arrow-right'),

                TextColumn::make('hardware_status.name')
                    ->sortable()
                    ->badge()
                    ->label('Estado')
                    ->color('success')
                    ->searchable()
                    ->iconPosition('after'),

                TextColumn::make('serial_number')
                    ->sortable()
                    ->label('Numero de Serie')
                    ->alignRight()
                    ->searchable()
                    ->badge()
                    ->color('info'),

                TextColumn::make('checked_out_at')
                    ->label('Asignado el')
                    ->alignRight(),

                TextColumn::make('checked_in_at')
                    ->label('Devuelto el')
                    ->alignRight(),
            ])
            ->filters([])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->label('Vincular Equipo'),
            ])
            ->actions([
                Tables\Actions\Action::make('check_in')
                    ->label('Desvincular')
                    ->action(function (Hardware $record) {
                        $record->pivot->find($record->pivot_id)->touch('checked_in_at');
                    })
                    ->requiresConfirmation()
                    ->visible(fn (Hardware $record) => empty($record->checked_in_at)),
            ]);
    }
}
