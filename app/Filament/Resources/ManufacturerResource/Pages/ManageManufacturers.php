<?php

namespace App\Filament\Resources\ManufacturerResource\Pages;

use App\Filament\Resources\ManufacturerResource;
use App\Traits\HasCustomFields;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Filament\Notifications\Notification;

class ManageManufacturers extends ManageRecords
{
    use HasCustomFields;

    protected static string $resource = ManufacturerResource::class;

    protected function getHeaderActions(): array
    {
        return [
        Actions\CreateAction::make()
            ->label('Registrar Fabricante')
            ->color('success')
            ->icon('heroicon-o-plus-circle')
                 // ✅ Oculta el botón "Crear y crear otro"
            ->createAnother(false)
            ->modalSubmitActionLabel('Registrar')
            ->using(function (array $data) {
                $record = $this->handleRecordCreation($data);

                Notification::make()
                    ->title('Fabricante creado exitosamente')
                    ->body('El fabricante ha sido registrado correctamente.')
                    ->success()
                    ->send();

                return $record;

            }),
    ];
    }
}
