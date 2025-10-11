<?php

namespace App\Filament\Resources\HardwareStatusResource\Pages;

use App\Filament\Resources\HardwareStatusResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditHardwareStatus extends EditRecord
{
    protected static string $resource = HardwareStatusResource::class;

      protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
        return null;
    }
    
    protected function afterSave()
    {
        Notification::make()
            ->title('Estado actualizado exitosamente')
            ->body('El estado de equipo informatico ha sido actualizado correctamente.')
            ->success()
            ->send();
    }
    

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->title('Estado eliminado exitosamente')
                        ->body('El estado de equipo informatico ha sido eliminado correctamente.')
                        ->success()

                ),
        ];
    }
}
