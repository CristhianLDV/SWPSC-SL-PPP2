<?php

namespace App\Filament\Resources\PersonResource\Pages;

use App\Filament\Resources\PersonResource;
use App\Traits\HasCustomFields;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreatePerson extends CreateRecord
{
    use HasCustomFields;

    protected static string $resource = PersonResource::class;
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
            ->title('Docente creado exitosamente')
            ->body('El docente ha sido creado correctamente.')
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
