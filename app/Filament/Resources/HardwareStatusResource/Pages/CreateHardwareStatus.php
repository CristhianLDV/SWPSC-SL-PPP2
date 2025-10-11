<?php

namespace App\Filament\Resources\HardwareStatusResource\Pages;

use App\Filament\Resources\HardwareStatusResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateHardwareStatus extends CreateRecord
{
    protected static string $resource = HardwareStatusResource::class;
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
            ->title('Estado creado exitosamente')
            ->body('El estado de equipo informatico ha sido creado correctamente.')
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
