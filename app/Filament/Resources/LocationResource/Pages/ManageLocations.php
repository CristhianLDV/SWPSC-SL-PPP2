<?php

namespace App\Filament\Resources\LocationResource\Pages;

use App\Filament\Resources\LocationResource;
use App\Traits\HasCustomFields;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Filament\Notifications\Notification;

class ManageLocations extends ManageRecords
{
    use HasCustomFields;

    protected static string $resource = LocationResource::class;

    protected function getHeaderActions(): array
    {
       return [
        Actions\CreateAction::make()
            ->label('Registrar Ubicación')
            ->color('success')
            ->icon('heroicon-o-plus-circle')
    
            ->createAnother(false)
            ->modalSubmitActionLabel('Registrar')
       
            ->using(function (array $data) {
                $record = $this->handleRecordCreation($data);

                Notification::make()
                    ->title('Ubicación creada exitosamente')
                    ->body('La ubicación ha sido registrada correctamente.')
                    ->success()
                    ->send();

                return $record;

            }),
    ];
    }
}
