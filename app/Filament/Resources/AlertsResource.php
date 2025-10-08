<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AlertsResource\Pages;
use App\Models\Alert;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class AlertsResource extends Resource
{
    protected static ?string $model = Alert::class;

    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getModelLabel(): string
    {
        return __('Alerta');
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() > 0 ? 'danger' : 'primary';
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Alert::query())
            ->columns([
                TextColumn::make('record')
                    ->label('Tipo de registro')
                    ->badge(),

                TextColumn::make('record_name')
                    ->label('Nombre del registro')
                    ->badge()
               
                    ->url(fn (Alert $record) => "/admin/{$record->record_url}/{$record->record_id}/edit")
                    ->iconPosition('after')
                    ->icon('heroicon-o-arrow-right'),

                TextColumn::make('threshold')
                    ->label('Umbral')
                    ->alignRight(),

                TextColumn::make('quantity_left')
                    ->label('Cantidad restante')
                    ->formatStateUsing(fn (string $state, Model $record): string => $record->quantity_left . ' de ' . $record->quantity)
                    ->alignRight(),
            ])
            ->filters([])
            ->actions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAlerts::route('/'),
        ];
    }
}
