<?php

namespace App\Filament\Resources\HardwareResource\Pages;

use App\Filament\Resources\HardwareResource;
use App\Traits\HasCustomFields;
use Filament\Notifications\Notification;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHardware extends EditRecord
{
    use HasCustomFields;

    protected static string $resource = HardwareResource::class;

    public function hasCombinedRelationManagerTabsWithContent(): bool
    {
        return true;
    }
/* 
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    } */
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
            ->title('Equipo informatico actualizado exitosamente')
            ->body('El equipo informatico ha sido actualizado correctamente.')
            ->success()
            ->send();
    }
    

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->title('Equipo informatico eliminado exitosamente')
                        ->body('El Equipo informatico ha sido eliminado correctamente.')
                        ->success()

                ),
        ];
    }

}
