<?php

namespace App\Filament\Resources\HardwareResource\Pages;

use App\Filament\Resources\HardwareResource;
use App\Traits\HasCustomFields;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateHardware extends CreateRecord
{
    use HasCustomFields;

    protected static string $resource = HardwareResource::class;

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
            ->title('Equipo informatico creado exitosamente')
            ->body('El equipo informatico ha sido creado correctamente.')
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
