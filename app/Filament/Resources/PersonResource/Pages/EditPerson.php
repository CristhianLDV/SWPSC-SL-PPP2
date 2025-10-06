<?php

namespace App\Filament\Resources\PersonResource\Pages;

use App\Filament\Resources\PersonResource;
use App\Traits\HasCustomFields;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditPerson extends EditRecord
{
    use HasCustomFields;

    protected static string $resource = PersonResource::class;

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
            ->title('Docente actualizado exitosamente')
            ->body('El docente ha sido actualizado correctamente.')
            ->success()
            ->send();
    }
    

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->title('Docente eliminado exitosamente')
                        ->body('El docente ha sido eliminado correctamente.')
                        ->success()

                ),
        ];
    }
}
