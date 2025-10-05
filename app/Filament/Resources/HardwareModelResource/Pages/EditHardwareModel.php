<?php

namespace App\Filament\Resources\HardwareModelResource\Pages;

use App\Filament\Resources\HardwareModelResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditHardwareModel extends EditRecord
{
    protected static string $resource = HardwareModelResource::class;

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
            ->title('Modelo de hardware actualizado exitosamente')
            ->body('El modelo de hardware ha sido actualizado correctamente.')
            ->success()
            ->send();
    }
    

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->title('Modelo de hardware eliminado exitosamente')
                        ->body('El modelo de hardware ha sido eliminado correctamente.')
                        ->success()

                ),
        ];
    }
}
