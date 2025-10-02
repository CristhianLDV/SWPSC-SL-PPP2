<?php

namespace App\Filament\Resources\Shared;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;

class ImagesAndNoteComponent
{
    public static function render()
    {
        return Section::make('Notas')
            ->description('Anote cualquier detalle importante')
            ->schema([
                Textarea::make('notes')
                    ->label('Notas'),
            ])
            ->collapsible()
            ->compact();
    }
}
