<?php

namespace App\Filament\Resources\LicenceResource\Pages;

use App\Filament\Resources\LicenceResource;

use App\Traits\HasCustomFields;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateLicence extends CreateRecord
{
    use HasCustomFields;

    protected static string $resource = LicenceResource::class;
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
            ->title('Licencia creada exitosamente')
            ->body('La licencia ha sido creada correctamente.')
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
