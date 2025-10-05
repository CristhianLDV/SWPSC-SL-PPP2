<?php

namespace App\Filament\Resources\HardwareModelResource\Pages;

use App\Filament\Resources\HardwareModelResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateHardwareModel extends CreateRecord
{
    protected static string $resource = HardwareModelResource::class;
 protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
   
    }
    protected function getCreatedNotification(): ?Notification
    {
        return null;
    }
    protected function afterCreate()
    {
        Notification::make()
            ->title('Modelo de hardware creado exitosamente')
            ->body('El modelo de hardware ha sido creado correctamente.')
            ->success()
            ->send();   
    }
    
    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()
            ->label('Registrar')
            ->color('success'),

           // $this->getCreateAnotherFormAction()
               // ->label('Guardar y Nuevo'),

            $this->getCancelFormAction()
                ->label('Cancelar'),


        ];
    }
}