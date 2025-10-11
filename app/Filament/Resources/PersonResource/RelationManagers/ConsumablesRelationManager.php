<?php

namespace App\Filament\Resources\PersonResource\RelationManagers;

use App\Models\Consumable;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ConsumablesRelationManager extends RelationManager
{
    protected static string $relationship = 'consumables';
    protected static ?string $recordTitleAttribute = 'name';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
            return 'Consumibles'; // Traducción del título de la pestaña
    }

    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        return $ownerRecord->consumables()->count();
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('Nombre del consumible')
                ->required()
                ->maxLength(255),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->allowDuplicates()
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre')
                    ->badge()
                    ->url(fn (Consumable $record) => "/admin/consumables/{$record->consumable_id}/edit")
                    ->getStateUsing(fn (Consumable $record): string => $record->name)
                    ->iconPosition('after')
                    ->icon('heroicon-o-arrow-right')
                    ->searchable(),

                TextColumn::make('model_number')
                    ->label('Modelo')
                    ->badge()
                    ->color('info')
                    ->searchable(),

                TextColumn::make('order_number')
                    ->label('Número de orden')
                    ->badge()
                    ->color('info')
                    ->searchable(),

                TextColumn::make('checked_out_at')
                    ->label('Fecha de entrega')
                    ->alignRight(),
            ])
            ->filters([])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->label('Vincular consumible'),
            ]);
    }
}
