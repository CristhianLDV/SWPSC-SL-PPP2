<?php

namespace App\Filament\Resources\Shared;

use Filament\Forms\Components\BelongsToSelect;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;

class ClsmComponent
{
    public static function render($withManufacturer = true)
    {
        $schema = [
            BelongsToSelect::make('department_id')
                ->relationship('department', 'name')
                ->label('Área')
                ->createOptionForm([
                    TextInput::make('name')
                        ->label('Nombre')
                        ->required()
                        ->maxLength(255),
                ])
                ->editOptionForm([
                    TextInput::make('name')
                        ->label('Nombre')
                        ->required()
                        ->maxLength(255),
                ])
                ->searchable()
                ->preload(),
            BelongsToSelect::make('location_id')
                ->label('Ubicación')
                ->relationship('location', 'name')
                ->createOptionForm([
                    TextInput::make('name')
                        ->label('Nombre')
                        ->required()
                        ->maxLength(255),
                ])
                ->editOptionForm([
                    TextInput::make('name')
                        ->label('Nombre')
                        ->required()
                        ->maxLength(255),
                ])
                ->searchable()
                ->preload(),
            BelongsToSelect::make('supplier_id')
                ->label('Proveedor')
                ->relationship('supplier', 'name')
                ->createOptionForm([
                    TextInput::make('name')
                        ->label('Nombre')
                        ->required()
                        ->maxLength(255),
                ])
                ->editOptionForm([
                    TextInput::make('name')
                        ->label('Nombre')
                        ->required()
                        ->maxLength(255),
                ])
                ->searchable()
                ->preload(),
        ];

        if ($withManufacturer) {
            $schema[] = BelongsToSelect::make('manufacturer_id')
                ->relationship('manufacturer', 'name')
                ->label('Fabricante')
                ->createOptionForm([
                    TextInput::make('name')
                        ->label('Nombre')
                        ->required()
                        ->maxLength(255),
                ])
                ->editOptionForm([
                    TextInput::make('name')
                        ->label('Nombre')
                        ->required()
                        ->maxLength(255),
                ])
                ->searchable()
                ->preload();
        }

        return Section::make('Envío de detalles del activo')
            ->description('Proporcione información detallada sobre su activo')
            ->collapsible()
            ->compact()
            ->columns($withManufacturer ? 4 : 3)
            ->schema($schema);
    }
}
