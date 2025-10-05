<?php

namespace App\Filament\Resources\ConsumableResource\Pages;

use App\Filament\Resources\ConsumableResource;
use App\Traits\HasCustomFields;
use Filament\Notifications\Notification;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditConsumable extends EditRecord
{
    use HasCustomFields;

    protected static string $resource = ConsumableResource::class;

    public function hasCombinedRelationManagerTabsWithContent(): bool
    {
        return true;
    }

   /*  protected function getHeaderActions(): array
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
            ->title('Consumible actualizado exitosamente')
            ->body('El consumible ha sido actualizado correctamente.')
            ->success()
            ->send();
    }
    

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->title('Consumible eliminado exitosamente')
                        ->body('El consumible ha sido eliminado correctamente.')
                        ->success()

                ),
        ];
    }
}
