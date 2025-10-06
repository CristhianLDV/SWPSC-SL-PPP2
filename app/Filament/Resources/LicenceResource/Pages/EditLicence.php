<?php

namespace App\Filament\Resources\LicenceResource\Pages;

use App\Filament\Resources\LicenceResource;
use App\Traits\HasCustomFields;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditLicence extends EditRecord
{
    use HasCustomFields;

    protected static string $resource = LicenceResource::class;

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
            ->title('Licencia actualizada exitosamente')
            ->body('La licencia ha sido actualizada correctamente.')
            ->success()
            ->send();
    }
    

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->title('Licencia eliminada exitosamente')
                        ->body('La licencia ha sido eliminada correctamente.')
                        ->success()

                ),
        ];
    }
}
