<?php

namespace App\Filament\Resources\Shared;

use Filament\Tables\Columns\TextColumn;

class CreatedAtUpdatedAtComponent
{
    public static function render()
    {
        return [
            TextColumn::make('created_at')
                ->dateTime()
                ->label('Fecha de Creación
                ')
                ->sortable()
                ->alignRight()
                ->toggleable(),
            TextColumn::make('updated_at')
                ->dateTime()
                ->label('Fecha de Actualización
                ')  
                ->sortable()
                ->alignRight()
                ->toggleable(),
        ];
    }
}
