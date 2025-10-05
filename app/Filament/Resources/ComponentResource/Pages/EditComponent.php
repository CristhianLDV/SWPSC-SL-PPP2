<?php

namespace App\Filament\Resources\ComponentResource\Pages;

use App\Filament\Resources\ComponentResource;
use App\Traits\HasCustomFields;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditComponent extends EditRecord
{
    use HasCustomFields;

    protected static string $resource = ComponentResource::class;

    public function hasCombinedRelationManagerTabsWithContent(): bool
    {
        return true;
    }

/*     protected function getHeaderActions(): array
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
            ->title('Componente actualizado exitosamente')
            ->body('El componente ha sido actualizado correctamente.')
            ->success()
            ->send();
    }
    

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->title('Componente eliminado exitosamente')
                        ->body('El componente ha sido eliminado correctamente.')
                        ->success()

                ),
        ];
    }


}
