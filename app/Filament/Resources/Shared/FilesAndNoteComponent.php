<?php

namespace App\Filament\Resources\Shared;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\SpatieMediaLibraryPlugin\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;

class FilesAndNoteComponent
{
    public static function render()
    {
        return Section::make('Archivos y notas')
            ->description('Adjunta archivos y anota cualquier detalle importante')
            ->schema([
                // FileUpload::make('files')->multiple(),
                SpatieMediaLibraryFileUpload::make('files')
                    ->imagePreviewHeight('250')
                    ->multiple() // o 
                    ->multiple(true)
                    ->enableReordering()
                    ->responsiveImages()
                    ->conversion('thumb'),
                Textarea::make('notes')
                    ->label('Notas'),
            ])
            ->collapsible()
            ->compact()
            ->columns(2);
    }
}
