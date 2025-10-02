<?php

namespace App\Filament\Resources\Shared;

use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;

class NcqComponent
{
    public static function render()
    {
        return Grid::make()->schema([
            Section::make('Información básica del componente')
                ->description('Proporcione detalles para facilitar un seguimiento preciso y efectivo')
                ->collapsible()
                ->compact()
                ->columns(3)
                ->schema([
                    TextInput::make('name')
                        ->label('Nombre')
                        ->maxLength(255)
                        ->required(),
                    TextInput::make('quantity')
                        ->label('Cantidad')
                        ->required()
                        ->minValue(0)
                        ->integer()
                        ->label('Cantidad'),
                    TextInput::make('threshold')
                        ->label('Umbral de alerta')
                        ->required()
                        ->integer(),
                ])
                ->columnSpan(3),
            Section::make('Código QR')
                ->columnSpan(1)
                ->collapsible()
                ->compact()
                ->schema([ViewField::make('qr_code')->view('filament.components.qr_code')]),
        ])->columns(4);
    }
}
