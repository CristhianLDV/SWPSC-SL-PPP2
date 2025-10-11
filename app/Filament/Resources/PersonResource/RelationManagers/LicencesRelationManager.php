<?php

namespace App\Filament\Resources\PersonResource\RelationManagers;

use App\Models\Licence;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class LicencesRelationManager extends RelationManager
{
    protected static string $relationship = 'licences';
    protected static ?string $recordTitleAttribute = 'name';
    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
            return 'Licencias'; // Traducción del título de la pestaña
    }
    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        return $ownerRecord->licences()->count();
    }

    public function table(Table $table): Table
    {
        return $table
            ->allowDuplicates()
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre')
                    ->badge()
                    ->url(fn (Licence $record) => "/admin/licences/{$record->licence_id}/edit")
                    ->getStateUsing(fn (Licence $record): string => $record->name)
                    ->iconPosition('after')
                    ->icon('heroicon-o-arrow-right')
                    ->searchable(),

                TextColumn::make('licensed_to_name')
                    ->label('Licenciado a')
                    ->searchable(),

                TextColumn::make('product_key')
                    ->label('Clave de producto')
                    ->badge()
                    ->color('info')
                    ->searchable(),

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
                    ->label('Vincular licencia'),
            ])
            ->actions([
                Tables\Actions\Action::make('check_in')
                    ->label('Desvincular')
                    ->requiresConfirmation()
                    // ✅ Acceso seguro al pivot
                    ->action(function (Licence $record) {
                        $record->pivot?->touch('checked_in_at');
                    })
                    ->visible(fn (Licence $record) => empty($record->checked_in_at)),
            ]);
    }
}
