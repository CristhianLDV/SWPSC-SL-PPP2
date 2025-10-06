<?php

namespace App\Filament\Resources\MaintenanceResource\Pages;

use App\Filament\Resources\MaintenanceResource;
use App\Traits\HasCustomFields;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Filament\Notifications\Notification;

class ManageMaintenances extends ManageRecords
{
    use HasCustomFields;

    protected static string $resource = MaintenanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
        Actions\CreateAction::make()
            ->label('Registrar Mantenimiento')
            ->color('success')
            ->icon('heroicon-o-plus-circle')
                 // ✅ Oculta el botón "Crear y crear otro"
            ->createAnother(false)
            ->modalSubmitActionLabel('Registrar')
            ->using(function (array $data) {
                $record = $this->handleRecordCreation($data);

                Notification::make()
                    ->title('Mantenimiento creado exitosamente')
                    ->body('El mantenimiento ha sido registrado correctamente.')
                    ->success()
                    ->send();

                return $record;

            }),
    ];
    }
}
